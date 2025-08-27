<?php

namespace App\Livewire\Automotores\Copres\Index;

use App\Models\Automotores\Copres;
use App\Models\Automotores\Vehiculo;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search = '';
    public $fecha_desde = '';
    public $fecha_hasta = '';
    public $vehiculo_filter = '';
    public $cuit_filter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'fecha_desde' => ['except' => ''],
        'fecha_hasta' => ['except' => ''],
        'vehiculo_filter' => ['except' => ''],
        'cuit_filter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFechaDesde()
    {
        $this->resetPage();
    }

    public function updatingFechaHasta()
    {
        $this->resetPage();
    }

    public function updatingVehiculoFilter()
    {
        $this->resetPage();
    }

    public function updatingCuitFilter()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'fecha_desde', 'fecha_hasta', 'vehiculo_filter', 'cuit_filter']);
        $this->resetPage();
    }

    // Métodos para los modales
    public function openEditModal($copresId)
    {
        $this->dispatch('openEditModal', $copresId);
    }

    public function openDeleteModal($copresId)
    {
        $this->dispatch('openDeleteModal', $copresId);
    }

    public function render()
    {
        $copres = Copres::with(['vehiculo', 'creator', 'chofer'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('numero_ticket_factura', 'like', '%' . $this->search . '%')
                      ->orWhere('cuit', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->fecha_desde, function ($query) {
                $query->where('fecha', '>=', $this->fecha_desde);
            })
            ->when($this->fecha_hasta, function ($query) {
                $query->where('fecha', '<=', $this->fecha_hasta);
            })
            ->when($this->vehiculo_filter, function ($query) {
                $query->where('vehiculo_id', $this->vehiculo_filter);
            })
            ->when($this->cuit_filter, function ($query) {
                $query->where('cuit', 'like', '%' . $this->cuit_filter . '%');
            })
            ->orderBy('fecha', 'desc')
            ->paginate(30);

        $vehiculos = Vehiculo::orderBy('marca')->orderBy('modelo')->get();

        return view('livewire.automotores.copres.index.search', compact('copres', 'vehiculos'));
    }
}
