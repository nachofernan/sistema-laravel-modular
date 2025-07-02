<?php

namespace App\Livewire\Usuarios\Users\Index;

use Livewire\Component;

class EliminarModal extends Component
{
    public $open = false;
    public $user;


    public function mount($user)
    {
        $this->user = $user;
    }

    public function delete_user()
    {
        $this->user->delete();
        return redirect()->route('usuarios.users.index');
    }

    public function render()
    {
        return view('livewire.usuarios.users.index.eliminar-modal');
    }
}
