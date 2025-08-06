<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class BorrarDocumentoApoderado extends Component
{
    public $open = false;
    public $documento; 

    public function render()
    {
        return view('livewire.proveedores.proveedors.show.borrar-documento-apoderado');
    }
} 