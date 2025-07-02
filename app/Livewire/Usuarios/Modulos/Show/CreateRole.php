<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use App\Models\Usuarios\Role;
use Livewire\Component;

class CreateRole extends Component
{
    public $open = false;
    public $modulo;
    public $nombre;
    public $permisos = [];

    public function mount($modulo)
    {
        $this->modulo = $modulo;
    }

    public function create()
    {
        $nombre = ucfirst($this->modulo->nombre)."/".$this->nombre;
        $role = Role::create(['name' => $nombre]);
        $role->syncPermissions($this->permisos);
        return redirect()->route('usuarios.modulos.show', $this->modulo);
    }

    public function render()
    {
        return view('livewire.usuarios.modulos.show.create-role');
    }
}
