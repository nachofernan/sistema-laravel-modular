<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class EditarContacto extends Component
{
    public $open = false;
    public $contacto;
    
    public function render()
    {
        return view('livewire.proveedores.proveedors.show.editar-contacto');
    }
}
