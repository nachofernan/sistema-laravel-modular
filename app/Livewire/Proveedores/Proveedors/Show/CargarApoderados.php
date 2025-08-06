<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use Livewire\Component;

class CargarApoderados extends Component
{
    public $open = false; 
    public $proveedor;
    public $contexto = 'apoderado'; // 'apoderado' o 'representante'
    
    public function mount($proveedor, $contexto = 'apoderado')
    {
        $this->proveedor = $proveedor;
        $this->contexto = $contexto;
    }
    
    public function render()
    {
        return view('livewire.proveedores.proveedors.show.cargar-apoderados');
    }
}
