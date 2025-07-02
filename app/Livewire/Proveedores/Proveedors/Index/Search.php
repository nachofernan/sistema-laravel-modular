<?php

namespace App\Livewire\Proveedores\Proveedors\Index;

use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Rubro;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search;
    public $show;
    public $rubro = 0;
    public $subrubro = 0;
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

    // Listener para cuando se selecciona un rubro/subrubro desde el modal
    #[On('rubro-selected')]
    public function setRubro($rubroId, $subrubroId = null)
    {
        $this->rubro = $rubroId;
        
        // Si hay subrubro, esperamos un tick para que se actualice el DOM
        if ($subrubroId) {
            $this->dispatch('set-subrubro-delayed', subrubroId: $subrubroId);
        } else {
            $this->subrubro = 0;
        }
    }

    #[On('set-subrubro-delayed')]
    public function setSubrubroDelayed($subrubroId)
    {
        // Pequeño delay para asegurar que el select se haya actualizado
        $this->subrubro = $subrubroId;
    }

    public function clearRubro()
    {
        $this->rubro = 0;
        $this->subrubro = 0;
    }

    public function render()
    {
        $proveedors = Proveedor::where(function ($query) {
            $query->where('razonsocial', 'LIKE', '%' . $this->search . '%')
                ->orWhere('cuit', 'LIKE', '%' . $this->search . '%')
                // Buscar también en subrubros y rubros
                ->orWhereHas('subrubros', function ($subQuery) {
                    $subQuery->where('nombre', 'LIKE', '%' . $this->search . '%')
                        ->orWhereHas('rubro', function ($rubroQuery) {
                            $rubroQuery->where('nombre', 'LIKE', '%' . $this->search . '%');
                        });
                });
        });
        
        if ($this->show != 0) {
            $proveedors->where('estado_id', $this->show);
        } else {
            $proveedors = $proveedors->where('estado_id', '!=', 4);
        }

        if ($this->rubro) {
            if ($this->subrubro) {
                $proveedors = $proveedors->whereHas('subrubros', function ($query) {
                    $query->where('subrubros.id', $this->subrubro);
                });
            } else { 
                $proveedors = $proveedors->whereHas('subrubros.rubro', function ($query) {
                    $query->where('rubros.id', $this->rubro);
                });
            }
        }
        
        //$proveedors = $proveedors->orderBy('razonsocial', 'asc')->paginate(30);
        $proveedors = $proveedors->orderBy('razonsocial', 'asc')->get();
        
        switch ($this->vencimiento) {
            case 1:
                $proveedors = $proveedors->filter(function($proveedor) {
                    return $proveedor->falta_a_vencimiento() > 30;
                });
                break;
            case 2:
                $proveedors = $proveedors->filter(function($proveedor) {
                    return $proveedor->falta_a_vencimiento() < 30 && $proveedor->falta_a_vencimiento() > 0;
                });
                break;
            case 3:
                $proveedors = $proveedors->filter(function($proveedor) {
                    return $proveedor->falta_a_vencimiento() < 0;
                });
                break;
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 30;
        $currentPageItems = $proveedors->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedProveedores = new LengthAwarePaginator($currentPageItems, $proveedors->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $rubros = Rubro::all();
        $rubro_sel = $this->rubro ? Rubro::find($this->rubro) : null;

        return view('livewire.proveedores.proveedors.index.search', ['proveedors' => $paginatedProveedores, 'rubros' => $rubros, 'rubro_sel' => $rubro_sel]);
    }
}
