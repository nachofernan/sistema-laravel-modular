<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use App\Models\Usuarios\Role;
use Livewire\Component;

class RoleUsers extends Component
{
    public $open = false;
    public $modulo;
    public $role;
    public $users = [];

    public function mount($modulo, $role)
    {
        $this->modulo = $modulo;
        $this->role = $role;
    }

    public function showUsers()
    {
        $this->users = $this->role->users()->orderBy('name')->get();
        $this->open = true;
    }

    public function render()
    {
        $usersCount = $this->role->users()->count();
        
        return view('livewire.usuarios.modulos.show.role-users', [
            'usersCount' => $usersCount
        ]);
    }
}
