<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Services\EmailDomainValidatorService;
use App\Services\EmailDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmailQueueController extends Controller
{
    /**
     * Mostrar el panel de administración de correos
     */
    public function index(Request $request)
    {
        // Obtener estadísticas generales
        $estadisticas = EmailDispatcher::obtenerEstadisticas();

        // Obtener jobs pendientes con paginación
        $jobsPendientes = DB::table('jobs')
            ->whereIn('queue', ['emails', 'emails-priority'])
            ->orderBy('available_at', 'asc')
            ->paginate(20);
            
        // Decodificar información de cada job
        $jobsPendientes->getCollection()->transform(function ($job) {
            $payload = json_decode($job->payload, true);
            $jobData = unserialize($payload['data']['command']);
            if (method_exists($jobData, 'getJobDescription')) {
                $info = $jobData->getJobDescription();
            } else {
                $info = [
                    'tipo' => 'Desconocido',
                    'destinatario' => 'Desconocido',
                    'descripcion' => 'No se pudo obtener descripción',
                    'prioridad' => 'normal'
                ];
            }
            
            return (object) [
                'id' => $job->id,
                'queue' => $job->queue,
                'tipo' => $info['tipo'] ?? 'N/A',
                'destinatario' => $info['destinatario'] ?? 'N/A',
                'descripcion' => $info['descripcion'] ?? 'Sin descripción',
                'prioridad' => $info['prioridad'] ?? 'normal',
                'programado_para' => Carbon::createFromTimestamp($job->available_at)->timezone(config('app.timezone')),
                'intentos' => $job->attempts,
                'created_at' => Carbon::createFromTimestamp($job->created_at)
            ];
        });

        // Obtener logs recientes
        $logsRecientes = DB::table('email_logs')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($log) {
                return (object) [
                    'id' => $log->id,
                    'destinatario' => $log->destinatario,
                    'tipo' => $log->tipo,
                    'descripcion' => $log->descripcion,
                    'estado' => $log->estado,
                    'error' => $log->error,
                    'created_at' => Carbon::parse($log->created_at)
                ];
            });

        return view('usuarios.email-queue.index', compact('estadisticas', 'jobsPendientes', 'logsRecientes'));
    }

    /**
     * Cambiar estado de envíos automáticos
     */
    public function toggleEnvios(Request $request)
    {
        $nuevoEstado = $request->input('enabled', false);
        
        // Actualizar variable de entorno (temporalmente)
        config(['mail.automated_sending_enabled' => $nuevoEstado]);
        
        // Aquí podrías también actualizar el archivo .env si es necesario
        // $this->updateEnvFile('MAIL_AUTOMATED_SENDING_ENABLED', $nuevoEstado ? 'true' : 'false');

        return response()->json([
            'success' => true,
            'message' => $nuevoEstado ? 
                'Envíos automáticos habilitados' : 
                'Envíos automáticos deshabilitados',
            'estado' => $nuevoEstado
        ]);
    }

    /**
     * Reajustar tiempos de la cola
     */
    public function reajustarCola()
    {
        $jobsActualizados = EmailDispatcher::reajustarCola();

        return response()->json([
            'success' => true,
            'message' => "Se reajustaron {$jobsActualizados} trabajos en la cola",
            'jobs_actualizados' => $jobsActualizados
        ]);
    }

    /**
     * Eliminar job específico
     */
    public function eliminarJob(Request $request)
    {
        $jobId = $request->input('job_id');
        
        $eliminado = DB::table('jobs')
            ->where('id', $jobId)
            ->delete();

        if ($eliminado) {
            return response()->json([
                'success' => true,
                'message' => 'Job eliminado exitosamente'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo eliminar el job'
        ], 400);
    }

    /**
     * Limpiar logs antiguos
     */
    public function limpiarLogs(Request $request)
    {
        $dias = $request->input('dias', 30);
        
        $eliminados = DB::table('email_logs')
            ->where('created_at', '<', now()->subDays($dias))
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Se eliminaron {$eliminados} registros de logs",
            'registros_eliminados' => $eliminados
        ]);
    }

    /**
     * Obtener estadísticas en tiempo real (AJAX)
     */
    public function estadisticas()
    {
        return response()->json(EmailDispatcher::obtenerEstadisticas());
    }

    /**
     * Actualizar archivo .env (método auxiliar)
     */
    private function updateEnvFile($key, $value)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";

        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            $envContent .= "\n{$replacement}";
        }

        file_put_contents($envFile, $envContent);
    }

    /**
     * Obtener estado del filtro de dominios
     */
    public function estadoFiltroDominio()
    {
        $estado = EmailDomainValidatorService::obtenerEstadoFiltro();
        $errores = EmailDomainValidatorService::validarConfiguracion();
        
        return response()->json([
            'filtro' => $estado,
            'errores_configuracion' => $errores,
            'configuracion_valida' => empty($errores)
        ]);
    }

    /**
     * Toggle del filtro de dominios
     */
    public function toggleFiltroDominio(Request $request)
    {
        $nuevoEstado = $request->input('enabled', false);
        
        // Actualizar configuración temporalmente
        config(['mail.domain_filter_enabled' => $nuevoEstado]);
        
        // Validar configuración si se está habilitando
        $errores = [];
        if ($nuevoEstado) {
            $errores = EmailDomainValidatorService::validarConfiguracion();
            
            if (!empty($errores)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede habilitar el filtro: configuración inválida',
                    'errores' => $errores
                ], 400);
            }
        }
        
        // Si necesitas persistir en .env
        // $this->updateEnvFile('MAIL_DOMAIN_FILTER_ENABLED', $nuevoEstado ? 'true' : 'false');

        return response()->json([
            'success' => true,
            'message' => $nuevoEstado ? 
                'Filtro de dominios habilitado' : 
                'Filtro de dominios deshabilitado',
            'estado' => $nuevoEstado,
            'configuracion' => EmailDomainValidatorService::obtenerEstadoFiltro()
        ]);
    }

    /**
     * Probar validación de un email específico
     */
    public function probarValidacionEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $email = $request->input('email');
        $puedeEnviar = EmailDomainValidatorService::puedeEnviarEmail($email);
        
        $resultado = EmailDomainValidatorService::procesarDestinatarios([$email]);
        
        return response()->json([
            'email' => $email,
            'puede_enviar' => $puedeEnviar,
            'resultado_procesamiento' => $resultado,
            'estado_filtro' => EmailDomainValidatorService::obtenerEstadoFiltro()
        ]);
    }

    /**
     * Obtener estadísticas extendidas incluyendo emails bloqueados
     */
    public function estadisticasExtendidas()
    {
        $estadisticasBase = EmailDispatcher::obtenerEstadisticas();
        
        // Agregar estadísticas de emails bloqueados
        $emailsBloqueados = DB::table('email_logs')
            ->where('estado', 'bloqueado')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
            
        $emailsRedirigidos = DB::table('email_logs')
            ->where('destinatario_original', '!=', null)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        return response()->json([
            'estadisticas_base' => $estadisticasBase,
            'filtro_dominio' => [
                'habilitado' => config('mail.domain_filter_enabled', false),
                'emails_bloqueados_7d' => $emailsBloqueados,
                'emails_redirigidos_7d' => $emailsRedirigidos
            ]
        ]);
    }
}