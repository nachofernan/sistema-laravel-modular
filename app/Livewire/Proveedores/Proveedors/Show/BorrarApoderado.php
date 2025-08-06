<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class BorrarApoderado extends Component
{
    public $open = false;
    public $apoderado; 

    public function render()
    {
        return view('livewire.proveedores.proveedors.show.borrar-apoderado');
    }
} 