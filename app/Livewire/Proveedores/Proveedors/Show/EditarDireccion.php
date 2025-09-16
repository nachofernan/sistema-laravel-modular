<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class EditarDireccion extends Component
{
    public $open = false;
    public $direccion;
    
    public function render()
    {
        return view('livewire.proveedores.proveedors.show.editar-direccion');
    }
}
