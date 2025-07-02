<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class CargarApoderados extends Component
{
    public $open = false; 
    public $proveedor;
    public $tipo = 'apoderado';
    
    public function render()
    {
        return view('livewire.proveedores.proveedors.show.cargar-apoderados');
    }
}
