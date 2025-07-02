<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class EditarApoderados extends Component
{
    public $open = false;
    public $apoderado;

    public function render()
    {
        return view('livewire.proveedores.proveedors.show.editar-apoderados');
    }
}
