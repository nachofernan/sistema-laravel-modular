<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class BorrarDireccion extends Component
{
    public $open = false;
    public $direccion; 

    public function render()
    {
        return view('livewire.proveedores.proveedors.show.borrar-direccion');
    }
}
