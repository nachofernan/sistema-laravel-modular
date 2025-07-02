<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class CargarContacto extends Component
{
    public $open = false; 
    public $proveedor;
    
    public function render()
    {
        return view('livewire.proveedores.proveedors.show.cargar-contacto');
    }
}
