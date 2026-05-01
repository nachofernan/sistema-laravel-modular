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
        $resultado = [
            'permitidos' => [],
            'bloqueados' => [],
            'redirigidos' => []
        ];
        $filterEnabled = config('mail.domain_filter_enabled', false);
        $comportamiento = config('mail.blocked_email_behavior', 'log');
        $emailRedireccion = config('mail.testing_redirect_email');

        foreach ($destinatarios as $destinatario) {
            $emailOriginal = is_array($destinatario) ? ($destinatario['email'] ?? '') : $destinatario;    
            
            if (!$filterEnabled) {
                $resultado['permitidos'][] = $destinatario;
                continue;
            }
            
            // Caso REDIRECT_ALL
            if ($comportamiento === 'redirect_all' && $emailRedireccion) {
                $resultado['redirigidos'][] = self::prepararDestinatarioRedirigido($destinatario, $emailRedireccion);
                $resultado['bloqueados'][] = $emailOriginal;
                continue;
            }
            
            if (self::esEmailPermitido($emailOriginal)) {
                $resultado['permitidos'][] = $destinatario;
            } else {
                $resultado['bloqueados'][] = $emailOriginal;
                if ($comportamiento === 'redirect' && $emailRedireccion) {
                    $resultado['redirigidos'][] = self::prepararDestinatarioRedirigido($destinatario, $emailRedireccion);
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
     * Mantiene la metadata original (nombre, etc) pero cambia el email
     */
    private static function prepararDestinatarioRedirigido($original, $nuevoEmail)
    {
        if (is_array($original)) {
            $clon = $original;
            $clon['email_original'] = $original['email'] ?? 'desconocido';
            $clon['email'] = $nuevoEmail;
            return $clon;
        }
        return $nuevoEmail;
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