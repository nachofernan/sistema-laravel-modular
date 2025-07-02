<?php

namespace App\Jobs\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\EmailDomainValidatorService;

class EnviarCorreoAutomatizado implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    protected $destinatario;
    protected $mailable;
    protected $tipo;
    protected $descripcion;
    protected $prioridad;

    public function __construct($destinatario, $mailable, $tipo, $descripcion = null, $prioridad = 'normal')
    {
        $this->destinatario = $destinatario;
        $this->mailable = $mailable;
        $this->tipo = $tipo;
        $this->descripcion = $descripcion ?? "Envío de {$tipo}";
        $this->prioridad = $prioridad;

        // Determinar cola según prioridad
        $cola = ($prioridad === 'alta') ? 'emails-priority' : 'emails';
        $this->onQueue($cola);
    }

    public function handle(): void
    {
        // Verificar si los envíos están habilitados
        if (!config('mail.automated_sending_enabled', true)) {
            Log::info('Envío de correo cancelado: envíos automáticos deshabilitados', [
                'destinatario' => $this->destinatario,
                'tipo' => $this->tipo
            ]);
            return;
        }

        try {
            // NUEVA VALIDACIÓN: Verificar filtro de dominios
            $resultadoValidacion = $this->validarDestinatario();
            
            if (!$resultadoValidacion['puede_enviar']) {
                $this->manejarEmailBloqueado($resultadoValidacion);
                return;
            }

            // Si hay redirección, cambiar destinatario
            $destinatarioFinal = $resultadoValidacion['destinatario_final'];

            // Verificar límite de velocidad antes del envío
            $this->verificarLimiteVelocidad();

            // Enviar el correo
            Mail::to($destinatarioFinal)->send($this->mailable);

            // Registrar el envío exitoso
            $this->registrarEnvio('exitoso', null, $destinatarioFinal);

            Log::info('Correo enviado exitosamente', [
                'destinatario_original' => $this->destinatario,
                'destinatario_final' => $destinatarioFinal,
                'tipo' => $this->tipo,
                'descripcion' => $this->descripcion,
                'fue_redirigido' => $destinatarioFinal !== $this->destinatario
            ]);

        } catch (\Exception $e) {
            // Registrar el error
            $this->registrarEnvio('fallido', $e->getMessage());
            
            Log::error('Error al enviar correo', [
                'destinatario' => $this->destinatario,
                'tipo' => $this->tipo,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * NUEVO: Valida el destinatario según las reglas de dominio
     */
    private function validarDestinatario(): array
    {
        $comportamiento = config('mail.blocked_email_behavior', 'log');
        
        // Si es redirect_all, redirigir TODO sin importar el dominio
        if ($comportamiento === 'redirect_all') {
            $emailRedireccion = config('mail.testing_redirect_email');
            if ($emailRedireccion) {
                return [
                    'puede_enviar' => true,
                    'destinatario_final' => $emailRedireccion,
                    'motivo' => 'redirect_all'
                ];
            }
        }

        $puedeEnviar = EmailDomainValidatorService::puedeEnviarEmail($this->destinatario);
        
        if ($puedeEnviar) {
            return [
                'puede_enviar' => true,
                'destinatario_final' => $this->destinatario,
                'motivo' => null
            ];
        }

        // Email bloqueado - verificar comportamiento
        switch ($comportamiento) {
            case 'redirect':
                $emailRedireccion = config('mail.testing_redirect_email');
                if ($emailRedireccion && EmailDomainValidatorService::puedeEnviarEmail($emailRedireccion)) {
                    return [
                        'puede_enviar' => true,
                        'destinatario_final' => $emailRedireccion,
                        'motivo' => 'redirigido'
                    ];
                }
                // Si no se puede redirigir, tratar como bloqueado
                return [
                    'puede_enviar' => false,
                    'destinatario_final' => $this->destinatario,
                    'motivo' => 'dominio_no_permitido'
                ];
                
            case 'throw':
                return [
                    'puede_enviar' => false,
                    'destinatario_final' => $this->destinatario,
                    'motivo' => 'dominio_no_permitido_throw'
                ];
                
            default: // 'log'
                return [
                    'puede_enviar' => false,
                    'destinatario_final' => $this->destinatario,
                    'motivo' => 'dominio_no_permitido'
                ];
        }
    }

    /**
     * NUEVO: Maneja emails bloqueados según configuración
     */
    private function manejarEmailBloqueado(array $resultadoValidacion)
    {
        $motivo = $resultadoValidacion['motivo'];
        
        // Registrar como bloqueado
        $this->registrarEnvio('bloqueado', "Email bloqueado por filtro de dominio: {$motivo}");
        
        Log::warning('Email bloqueado por filtro de dominio', [
            'destinatario' => $this->destinatario,
            'tipo' => $this->tipo,
            'motivo' => $motivo,
            'filtro_estado' => EmailDomainValidatorService::obtenerEstadoFiltro()
        ]);

        // Si el comportamiento es 'throw', lanzar excepción
        if ($motivo === 'dominio_no_permitido_throw') {
            throw new \Exception("Email bloqueado por filtro de dominio: {$this->destinatario}");
        }
    }

    /**
     * Verificar límite de velocidad - SIMPLE
     */
    private function verificarLimiteVelocidad()
    {
        $limitePorMinuto = (int) config('mail.rate_limit_per_minute', 20);
        $tiempoLimite = Carbon::now(config('app.timezone'))->subMinute();
        
        $enviosRecientes = DB::table('email_logs')
            ->where('estado', 'exitoso')
            ->where('created_at', '>=', $tiempoLimite)
            ->count();

        if ($enviosRecientes >= $limitePorMinuto) {
            // Esperar hasta el próximo minuto
            $segundosEspera = 60 - Carbon::now()->second;
            sleep($segundosEspera);
        }
    }

    /**
     * Registrar el intento de envío (ACTUALIZADO para soportar destinatario final)
     */
    private function registrarEnvio($estado, $error = null, $destinatarioFinal = null)
    {
        $ahora = Carbon::now(config('app.timezone'));
        
        DB::table('email_logs')->insert([
            'destinatario' => $destinatarioFinal ?? $this->destinatario,
            'destinatario_original' => $this->destinatario !== ($destinatarioFinal ?? $this->destinatario) ? $this->destinatario : null,
            'tipo' => $this->tipo,
            'descripcion' => $this->descripcion,
            'estado' => $estado,
            'error' => $error,
            'created_at' => $ahora,
            'updated_at' => $ahora
        ]);
    }

    public function getJobDescription()
    {
        return [
            'tipo' => $this->tipo,
            'destinatario' => $this->destinatario,
            'descripcion' => $this->descripcion,
            'prioridad' => $this->prioridad
        ];
    }
}