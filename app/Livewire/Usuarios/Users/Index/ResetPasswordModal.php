<?php

namespace App\Livewire\Usuarios\Users\Index;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Usuarios\Log;
use Livewire\Component;

class ResetPasswordModal extends Component
{
    use PasswordValidationRules;

    public $open = false;

    public $user;

    public $pass_set = false;

    public $error = '';

    public $nueva_password;

    public function mount($user)
    {
        $this->user = $user;
    }

    /**
     * Un admin le pone una contraseña nueva a otro usuario (queda vencida, para forzar el cambio).
     * Se registra en `logs` (accesos del usuario) y en el historial con el admin como causante.
     *
     * Cubierto por: el_reset_por_admin_se_registra, un_usuario_sin_permiso_no_puede_resetear_contrasenas.
     */
    public function reset_password()
    {
        $this->authorize('Usuarios/Usuarios/Editar');

        if ($this->nueva_password && strlen($this->nueva_password) >= 8) {
            $this->user->update([
                'password' => bcrypt($this->nueva_password),
            ]);

            $this->user->passwordSecurity->update([
                'password_updated_at' => \Carbon\Carbon::now()->subDays(181),
            ]);

            Log::create([
                'user_id' => $this->user->id,
                'event' => 'password_reset_admin',
                'ip_address' => request()->ip(),
            ]);

            activity('usuarios')
                ->performedOn($this->user)
                ->event('password_reset')
                ->log('contraseña reseteada');

            $this->open = false;
            $this->pass_set = true;
            $this->error = '';
            // return redirect()->route('users.index');
        } else {
            $this->error = 'La contraseña debe tener al menos 8 caracteres';
        }
    }

    public function render()
    {
        return view('livewire.usuarios.users.index.reset-password-modal');
    }
}
