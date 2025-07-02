<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use App\Models\Proveedores\Proveedor;
use Livewire\Component;

class VerDocumentacionModal extends Component
{
    public $proveedor;
    public $open = false;
    public $documentoTipo;

    public function render()
    {
        return view('livewire.proveedores.proveedors.show.ver-documentacion-modal');
    }
}
