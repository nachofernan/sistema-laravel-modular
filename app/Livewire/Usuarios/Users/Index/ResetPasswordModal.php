<?php

namespace App\Livewire\Usuarios\Users\Index;

use App\Actions\Fortify\PasswordValidationRules;
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

    public function reset_password()
    {
        if($this->nueva_password && strlen($this->nueva_password) >= 8) {
            $this->user->update([
                'password' => bcrypt($this->nueva_password),
            ]);

            $this->user->passwordSecurity->update([
                'password_updated_at' => \Carbon\Carbon::now()->subDays(181),
            ]);

            $this->open = false;
            $this->pass_set = true;
            $this->error = '';
            //return redirect()->route('users.index');
        } else {
            $this->error = 'La contraseña debe tener al menos 8 caracteres';
        }
    }

    public function render()
    {
        return view('livewire.usuarios.users.index.reset-password-modal');
    }
}
