<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use App\Models\Usuarios\Permission;
use Livewire\Component;

class CreatePermission extends Component
{
    public $open = false;
    public $modulo;
    public $nombre;

    public function mount($modulo)
    {
        $this->modulo = $modulo;
    }

    public function create()
    {
        $nombre = ucfirst($this->modulo->nombre)."/".$this->nombre;
        Permission::create(['name' => $nombre]);
        return redirect()->route('usuarios.modulos.show', $this->modulo);
    }

    public function render()
    {
        return view('livewire.usuarios.modulos.show.create-permission');
    }
}
