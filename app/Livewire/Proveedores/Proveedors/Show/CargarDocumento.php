<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use App\Models\Proveedores\DocumentoTipo;
use Livewire\Component;

class CargarDocumento extends Component
{
    public $open = false; 
    public $proveedor;
    public $documentoTipos;
    public $documento_tipo_id;
    public $requiere_vencimiento = false;

    public function mount($proveedor)
    {
        $this->proveedor = $proveedor;
        $this->documentoTipos = DocumentoTipo::all();
    }

    public function updatedDocumentoTipoId($value)
    {
        if ($value) {
            $documentoTipo = DocumentoTipo::find($value);
            $this->requiere_vencimiento = $documentoTipo ? $documentoTipo->vencimiento : false;
        } else {
            $this->requiere_vencimiento = false;
        }
    }

    public function render()
    {
        return view('livewire.proveedores.proveedors.show.cargar-documento');
    }
}
