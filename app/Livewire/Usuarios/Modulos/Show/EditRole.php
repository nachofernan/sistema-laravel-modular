<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use Livewire\Component;

class EditRole extends Component
{
    public $open = false;
    public $modulo;
    public $role;
    public $nombre;
    public $test;
    public $permisos = [];

    public function mount($modulo, $role)
    {
        $this->modulo = $modulo;
        $this->role = $role;
        $parts = explode('/', $role->name, 2);
        $this->nombre = isset($parts[1]) ? $parts[1] : '';
        $this->permisos = $role->permissions->pluck('name')->toArray();
    }

    public function create()
    {
        $nombre = ucfirst($this->modulo->nombre)."/".$this->nombre;
        $this->role->update(['name' => $nombre]);
        $this->role->syncPermissions($this->permisos);
        return redirect()->route('usuarios.modulos.show', $this->modulo);
    }

    public function delete()
    {
        if($this->test == "1234") {
            $this->role->delete();
            return redirect()->route('usuarios.modulos.show', $this->modulo);
        }
    }

    public function render()
    {
        return view('livewire.usuarios.modulos.show.edit-role');
    }
}
