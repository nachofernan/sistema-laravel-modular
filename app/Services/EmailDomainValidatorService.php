<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class EmailDomainValidatorService
{
    /**
     * Valida si un email puede ser enviado según las reglas configuradas
     */
    public static function puedeEnviarEmail(string $email): bool
    {
        // Si el filtro no está habilitado, permitir todo
        if (!config('mail.domain_filter_enabled', false)) {
            return true;
        }

        return self::esEmailPermitido($email);
    }

    /**
     * Procesa una lista de destinatarios y filtra los no permitidos
     */
    public static function procesarDestinatarios(array $destinatarios): array
    {
        if (!config('mail.domain_filter_enabled', false)) {
            return $destinatarios;
        }

        $resultado = [
            'permitidos' => [],
            'bloqueados' => [],
            'redirigidos' => []
        ];

        $comportamiento = config('mail.blocked_email_behavior', 'log');

        foreach ($destinatarios as $email) {
            // Si el comportamiento es redirect_all, redirigir TODO
            if ($comportamiento === 'redirect_all') {
                $emailRedireccion = config('mail.testing_redirect_email');
                if ($emailRedireccion && !in_array($emailRedireccion, $resultado['redirigidos'])) {
                    $resultado['redirigidos'][] = $emailRedireccion;
                }
                $resultado['bloqueados'][] = $email; // Para logging
                continue;
            }

            if (self::esEmailPermitido($email)) {
                $resultado['permitidos'][] = $email;
            } else {
                $resultado['bloqueados'][] = $email;
                
                if ($comportamiento === 'redirect') {
                    $emailRedireccion = config('mail.testing_redirect_email');
                    if ($emailRedireccion && !in_array($emailRedireccion, $resultado['redirigidos'])) {
                        $resultado['redirigidos'][] = $emailRedireccion;
                    }
                }
            }
        }

        // Log de emails bloqueados
        if (!empty($resultado['bloqueados'])) {
            Log::warning('Emails procesados por filtro de dominio', [
                'emails_bloqueados' => $resultado['bloqueados'],
                'filtro_habilitado' => true,
                'comportamiento' => $comportamiento
            ]);
        }

        return $resultado;
    }

    /**
     * Verifica si un email específico está permitido
     */
    private static function esEmailPermitido(string $email): bool
    {
        // Normalizar email
        $email = strtolower(trim($email));

        // Verificar emails específicos permitidos
        $emailsPermitidos = array_map('strtolower', config('mail.allowed_emails', []));
        if (in_array($email, $emailsPermitidos)) {
            return true;
        }

        // Verificar dominios permitidos
        $dominiosPermitidos = array_map('strtolower', config('mail.allowed_domains', []));
        
        foreach ($dominiosPermitidos as $dominio) {
            if (str_ends_with($email, '@' . $dominio)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene información sobre el estado del filtro
     */
    public static function obtenerEstadoFiltro(): array
    {
        return [
            'habilitado' => config('mail.domain_filter_enabled', false),
            'dominios_permitidos' => config('mail.allowed_domains', []),
            'emails_permitidos' => config('mail.allowed_emails', []),
            'comportamiento_bloqueo' => config('mail.blocked_email_behavior', 'log'),
            'email_redireccion' => config('mail.testing_redirect_email')
        ];
    }

    /**
     * Valida configuración y devuelve errores si los hay
     */
    public static function validarConfiguracion(): array
    {
        $errores = [];

        if (config('mail.domain_filter_enabled', false)) {
            $dominios = config('mail.allowed_domains', []);
            $emails = config('mail.allowed_emails', []);
            
            if (empty($dominios) && empty($emails)) {
                $errores[] = 'Filtro de dominio habilitado pero no hay dominios ni emails permitidos configurados';
            }

            $comportamiento = config('mail.blocked_email_behavior', 'log');
            if ($comportamiento === 'redirect') {
                $emailRedireccion = config('mail.testing_redirect_email');
                if (empty($emailRedireccion)) {
                    $errores[] = 'Comportamiento de redirección configurado pero no hay email de destino';
                }
                
                if (!self::esEmailPermitido($emailRedireccion)) {
                    $errores[] = 'El email de redirección no está en la lista de permitidos';
                }
            }
        }

        return $errores;
    }
}