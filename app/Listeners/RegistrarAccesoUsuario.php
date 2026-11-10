<?php

namespace App\Listeners;

use App\Models\Usuarios\Log;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Laravel\Fortify\Events\PasswordUpdatedViaController;

/**
 * Log de accesos y seguridad (tabla `logs`, base usuarios) a partir de los eventos de auth.
 * Reemplaza al viejo middleware LogUserActivity, que no veía el login con 2FA, el logout ni
 * los resets por mail. Laravel lo registra solo (auto-discovery de app/Listeners).
 *
 * Cubierto por: el_login_registra_un_acceso, el_logout_registra_un_acceso,
 * un_login_fallido_se_registra, el_reset_por_mail_registra_un_cambio_de_contrasena.
 */
class RegistrarAccesoUsuario
{
    public function handleLogin(Login $evento): void
    {
        $this->registrar($evento->user->getAuthIdentifier(), 'login');
    }

    public function handleLogout(Logout $evento): void
    {
        if ($evento->user) {
            $this->registrar($evento->user->getAuthIdentifier(), 'logout');
        }
    }

    public function handleFailed(Failed $evento): void
    {
        // Si el email no corresponde a ningún usuario, queda sin user_id.
        $this->registrar($evento->user?->getAuthIdentifier(), 'login_fallido');
    }

    /** Reset por mail (controlador propio y Fortify disparan este evento). */
    public function handlePasswordReset(PasswordReset $evento): void
    {
        $this->registrar($evento->user->getAuthIdentifier(), 'password_change');
    }

    /** Cambio de contraseña desde el perfil de Jetstream. */
    public function handlePasswordUpdatedViaController(PasswordUpdatedViaController $evento): void
    {
        $this->registrar($evento->user->getAuthIdentifier(), 'password_change');
    }

    private function registrar(?int $userId, string $evento): void
    {
        Log::create([
            'user_id' => $userId,
            'event' => $evento,
            'ip_address' => request()->ip(),
        ]);
    }
}
