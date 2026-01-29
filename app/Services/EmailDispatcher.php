<?php

namespace App\Services;

use App\Jobs\Emails\EnviarCorreoAutomatizado;
use App\Models\Usuarios\ManagedJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailDispatcher
{
    /**
     * Enviar correos de forma espaciada - VERSIÓN SIMPLE
     */
    public static function enviarMasivo(array $destinatarios, $mailable, $tipo = 'general', $descripcion = '')
    {
        $proximoTiempo = self::obtenerProximoTiempo(); // Sin fecha específica = inmediato

        foreach ($destinatarios as $index => $destinatario) {
            $tiempoEjecucion = $proximoTiempo->copy()->addSeconds(3 * $index);

            // Clonar el mailable para cada destinatario
            $mailablePersonalizado = clone $mailable;
            // Solo asignar si la propiedad existe
            if (property_exists($mailablePersonalizado, 'destinatario')) {
                $mailablePersonalizado->destinatario = $destinatario;
            }

            $job = new EnviarCorreoAutomatizado(
                $destinatario,
                $mailablePersonalizado,
                $tipo,
                $descripcion
            );

            $job->delay($tiempoEjecucion);
            dispatch($job);
        }

        return count($destinatarios);
    }

    /**
     * Enviar correos con tracking - VERSIÓN SIMPLE
     */
    public static function enviarMasivoConTracking(
        array $destinatarios, 
        $mailable, 
        $entityType, 
        $entityId, 
        $jobType,
        $tipo = 'general', 
        $descripcion = '', 
        $fechaEjecucion = null,
        array $tags = []
    ) {
        // Obtener el próximo tiempo (inmediato o para fecha específica)
        $proximoTiempo = $fechaEjecucion 
            ? self::obtenerProximoTiempo($fechaEjecucion)
            : self::obtenerProximoTiempo();
        
        $jobsCreados = [];

        foreach ($destinatarios as $index => $destinatario) {
            $tiempoEjecucion = $proximoTiempo->copy()->addSeconds(3 * $index);

            // Clonar el mailable para cada destinatario
            $mailablePersonalizado = clone $mailable;
            // Solo asignar si la propiedad existe
            if (property_exists($mailablePersonalizado, 'destinatario')) {
                $mailablePersonalizado->destinatario = $destinatario;
            }

            $job = new EnviarCorreoAutomatizado(
                $destinatario,
                $mailablePersonalizado,
                $tipo,
                $descripcion
            );

            $job->delay($tiempoEjecucion);
            dispatch($job);
            
            $managedJob = ManagedJob::create([
                'job_uuid' => Str::uuid()->toString(),
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'job_type' => $jobType,
                'tags' => $tags,
                'scheduled_for' => $tiempoEjecucion,
                'metadata' => [
                    'destinatario' => $destinatario,
                    'descripcion' => $descripcion,
                ]
            ]);
            
            $jobsCreados[] = $managedJob;
        }

        return $jobsCreados;
    }

    /**
     * LA FUNCIÓN MÁGICA - Pero ahora SÍ simple y efectiva
     */
    private static function obtenerProximoTiempo($fechaEspecifica = null)
    {
        // Si es para una fecha específica, buscar en esa ventana de tiempo
        if ($fechaEspecifica) {
            $inicioVentana = $fechaEspecifica->copy()->subMinutes(5);
            $finVentana = $fechaEspecifica->copy()->addMinutes(5);
            
            $ultimoEnVentana = DB::table('jobs')
                ->where('queue', 'emails')
                ->whereBetween('available_at', [$inicioVentana->timestamp, $finVentana->timestamp])
                ->max('available_at');
                
            return $ultimoEnVentana 
                ? Carbon::createFromTimestamp($ultimoEnVentana, config('app.timezone'))->addSeconds(3)
                : $fechaEspecifica;
        }
        
        // Para envíos inmediatos, buscar solo en próximos 10 minutos
        $ahora = Carbon::now(config('app.timezone'));
        $limite = $ahora->copy()->addMinutes(10);
        
        $ultimoInmediato = DB::table('jobs')
            ->where('queue', 'emails')
            ->whereBetween('available_at', [$ahora->timestamp, $limite->timestamp])
            ->max('available_at');
        
        return $ultimoInmediato 
            ? Carbon::createFromTimestamp($ultimoInmediato, config('app.timezone'))->addSeconds(3)
            : $ahora->addSeconds(3);
    }

    public static function enviarPrioritario($destinatario, $mailable, $tipo = 'prioritario', $descripcion = '')
    {
        $job = new EnviarCorreoAutomatizado(
            $destinatario,
            $mailable,
            $tipo,
            $descripcion,
            'alta'
        );

        dispatch($job);
    }

    public static function obtenerEstadisticas()
    {
        $pendientes = DB::table('jobs')->where('queue', 'emails')->count();
        $prioritarios = DB::table('jobs')->where('queue', 'emails-priority')->count();
        $hoy = Carbon::today(config('app.timezone'));

        $enviadosHoy = DB::table('email_logs')
            ->where('estado', 'exitoso')
            ->whereDate('created_at', $hoy)
            ->count();

        $fallidosHoy = DB::table('email_logs')
            ->where('estado', 'fallido')
            ->whereDate('created_at', $hoy)
            ->count();

        $proximoEnvio = DB::table('jobs')
            ->where('queue', 'emails')
            ->orderBy('available_at', 'asc')
            ->first();

        return [
            'pendientes' => $pendientes,
            'prioritarios' => $prioritarios,
            'enviados_hoy' => $enviadosHoy,
            'fallidos_hoy' => $fallidosHoy,
            'proximo_envio' => $proximoEnvio ? 
                Carbon::createFromTimestamp($proximoEnvio->available_at, config('app.timezone')) : null,
            'envios_habilitados' => config('mail.automated_sending_enabled', true)
        ];
    }

    /**
     * Agregar emails a concurso existente - SIMPLE
     */
    public static function agregarEmailsAConcurso(
        $concurso,
        array $nuevosDestinatarios,
        $jobType,
        $mailable = null,
        $fechaEjecucion = null
    ) {
        if (empty($nuevosDestinatarios)) return [];

        // Determinar el mailable según el tipo
        if (!$mailable) {
            switch ($jobType) {
                case 'recordatorio_48hs':
                case 'recordatorio_manual':
                    $mailable = new \App\Mail\Concursos\ProximoCierre($concurso);
                    break;
                case 'notificacion_cierre_automatica':
                case 'notificacion_finalizacion':
                    $mailable = new \App\Mail\Concursos\ConcursoFinalizado($concurso);
                    break;
                default:
                    $mailable = new \App\Mail\Concursos\ConcursoAbierto($concurso);
            }
        }

        $descripcion = "Concurso #{$concurso->numero} - {$jobType}";
        $tags = ['concurso', $jobType];

        return self::enviarMasivoConTracking(
            $nuevosDestinatarios,
            $mailable,
            'concurso',
            $concurso->id,
            $jobType,
            'concurso',
            $descripcion,
            $fechaEjecucion,
            $tags
        );
    }

    /**
     * Cancelar jobs - SIMPLE
     */
    public static function cancelarJobsPorEntidad($entityType, $entityId, $jobType = null)
    {
        $query = ManagedJob::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->where('status', 'pending');
            
        if ($jobType) {
            $query->where('job_type', $jobType);
        }
        
        $jobs = $query->get();
        
        foreach ($jobs as $managedJob) {
            // Buscar y eliminar el job real de la cola
            $tiempoEjecucion = $managedJob->scheduled_for;
            $destinatario = $managedJob->metadata['destinatario'] ?? '';
            
            DB::table('jobs')
                ->where('queue', 'emails')
                ->whereBetween('available_at', [
                    $tiempoEjecucion->timestamp - 10,
                    $tiempoEjecucion->timestamp + 10
                ])
                ->where('payload', 'like', '%' . $destinatario . '%')
                ->delete();
            
            // Marcar como cancelado
            $managedJob->update(['status' => 'cancelled']);
        }
        
        return $jobs->count();
    }
}