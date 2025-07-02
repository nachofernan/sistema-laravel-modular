<?php

namespace App\Livewire\Proveedores\Proveedors\Index;

use App\Models\Proveedores\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;

class Eliminados extends Component
{
    use WithPagination;
    
    public $search;
    public $show;
    public $vencimiento = 0;

    public function updatedSearch() {
        $this->resetPage();
    }
    public function updatedShow() {
        $this->resetPage();
    }
    public function updatedVencimiento() {
        $this->resetPage();
    }

    public function render()
    {
        $proveedors = Proveedor::where(function ($query) {
            $query->where('razonsocial', 'LIKE', '%' . $this->search . '%')
                ->orWhere('cuit', 'LIKE', '%' . $this->search . '%');
        });
        
        $proveedors = $proveedors->where('estado_id', 4)->orderBy('razonsocial', 'asc')->paginate(30);
        
        return view('livewire.proveedores.proveedors.index.eliminados', ['proveedors' => $proveedors]);
        
    }
}
