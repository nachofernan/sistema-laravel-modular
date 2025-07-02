<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class BorrarContacto extends Component
{
    public $open = false;
    public $contacto; 

    public function render()
    {
        return view('livewire.proveedores.proveedors.show.borrar-contacto');
    }
}
