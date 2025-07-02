<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use App\Models\Usuarios\Permission;
use Livewire\Component;

class EditPermission extends Component
{
    public $open = false;
    public $modulo;
    public $permiso;
    public $nombre;
    public $test;

    public function mount($modulo, $permiso)
    {
        $this->modulo = $modulo;
        $this->permiso = $permiso;
        $parts = explode('/', $permiso->name, 2);
        $this->nombre = isset($parts[1]) ? $parts[1] : '';
    }

    public function create()
    {
        $nombre = ucfirst($this->modulo->nombre)."/".$this->nombre;
        $this->permiso->update(['name' => $nombre]);
        return redirect()->route('usuarios.modulos.show', $this->modulo);
    }

    public function delete()
    {
        if($this->test == "1234") {
            $this->permiso->delete();
            return redirect()->route('usuarios.modulos.show', $this->modulo);
        }
    }

    public function render()
    {
        return view('livewire.usuarios.modulos.show.edit-permission');
    }
}
