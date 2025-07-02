<?php

namespace App\Livewire\Usuarios\Users\Trashed;

use Livewire\Component;

class RestoreModal extends Component
{
    public $open = false;
    public $user;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function restore_user()
    {
        $this->user->restore();
        return redirect()->route('usuarios.users.trashed');
    }

    public function render()
    {
        return view('livewire.usuarios.users.trashed.restore-modal');
    }
}
