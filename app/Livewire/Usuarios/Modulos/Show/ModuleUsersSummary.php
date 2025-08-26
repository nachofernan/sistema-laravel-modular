<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use App\Models\Usuarios\Modulo;
use Livewire\Component;

class ModuleUsersSummary extends Component
{
    public $open = false;
    public $modulo;
    public $users;
    public $roleUsers;

    public function mount($modulo)
    {
        $this->modulo = $modulo;
    }

    public function showModuleUsers()
    {
        // Obtener todos los usuarios que tienen roles de este módulo
        $this->users = collect();
        $this->roleUsers = [];
        
        foreach ($this->modulo->roles() as $role) {
            $roleUsers = $role->users()->orderBy('name')->get();
            if ($roleUsers->count() > 0) {
                $this->roleUsers[$role->name] = $roleUsers;
                $this->users = $this->users->merge($roleUsers);
            }
        }
        
        // Eliminar duplicados (usuarios que pueden tener múltiples roles)
        $this->users = $this->users->unique('id')->sortBy('name');
        
        $this->open = true;
    }

    public function render()
    {
        $totalUsers = 0;
        $roleCounts = [];
        
        foreach ($this->modulo->roles() as $role) {
            $count = $role->users()->count();
            $roleCounts[$role->name] = $count;
            $totalUsers += $count;
        }
        
        return view('livewire.usuarios.modulos.show.module-users-summary', [
            'totalUsers' => $totalUsers,
            'roleCounts' => $roleCounts
        ]);
    }
}
