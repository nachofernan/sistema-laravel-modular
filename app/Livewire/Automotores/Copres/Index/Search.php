<?php

namespace App\Livewire\Automotores\Copres\Index;

use App\Models\Automotores\Copres;
use App\Models\Automotores\Vehiculo;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    // Filtros
    public $fecha_desde = '';
    public $fecha_hasta = '';
    public $vehiculo_filter = '';
    public $cuit_filter = '';
    public $kz_filter = '';
    public $ticket_filter = '';
    public $importe_desde = '';
    public $importe_hasta = '';
    public $solo_copias = false;
    
    // Ordenamiento
    public $sortBy = 'fecha';
    public $sortDirection = 'desc';

    protected $queryString = [
        'fecha_desde' => ['except' => ''],
        'fecha_hasta' => ['except' => ''],
        'vehiculo_filter' => ['except' => ''],
        'cuit_filter' => ['except' => ''],
        'kz_filter' => ['except' => ''],
        'ticket_filter' => ['except' => ''],
        'importe_desde' => ['except' => ''],
        'importe_hasta' => ['except' => ''],
        'solo_copias' => ['except' => false],
        'sortBy' => ['except' => 'fecha'],
        'sortDirection' => ['except' => 'desc'],
    ];

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

    public function updatingKzFilter()
    {
        $this->resetPage();
    }

    public function updatingTicketFilter()
    {
        $this->resetPage();
    }

    public function updatingImporteDesde()
    {
        $this->resetPage();
    }

    public function updatingImporteHasta()
    {
        $this->resetPage();
    }

    public function updatingSoloCopias()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset([
            'fecha_desde', 'fecha_hasta', 'vehiculo_filter', 'cuit_filter', 
            'kz_filter', 'ticket_filter', 'importe_desde', 'importe_hasta', 'solo_copias'
        ]);
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
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
        $copres = Copres::with(['vehiculo', 'creator'])
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
            ->when($this->kz_filter, function ($query) {
                $query->where('kz', 'like', '%' . $this->kz_filter . '%');
            })
            ->when($this->ticket_filter, function ($query) {
                $query->where('numero_ticket_factura', 'like', '%' . $this->ticket_filter . '%');
            })
            ->when($this->importe_desde, function ($query) {
                $query->where('importe_final', '>=', $this->importe_desde);
            })
            ->when($this->importe_hasta, function ($query) {
                $query->where('importe_final', '<=', $this->importe_hasta);
            })
            ->when($this->solo_copias, function ($query) {
                $query->where('es_original', false);
            })
            ->when($this->sortBy === 'vehiculo', function ($query) {
                $query->join('vehiculos', 'copres.vehiculo_id', '=', 'vehiculos.id')
                      ->orderBy('vehiculos.marca', $this->sortDirection)
                      ->orderBy('vehiculos.modelo', $this->sortDirection)
                      ->select('copres.*');
            }, function ($query) {
                // Para ordenamiento por columnas que pueden tener valores NULL, los colocamos al final
                if (in_array($this->sortBy, ['kz'])) {
                    $query->orderByRaw("CASE WHEN {$this->sortBy} IS NULL OR {$this->sortBy} = '' THEN 1 ELSE 0 END")
                          ->orderBy($this->sortBy, $this->sortDirection);
                } else {
                    $query->orderBy($this->sortBy, $this->sortDirection);
                }
            })
            ->paginate(30);

        $vehiculos = Vehiculo::orderBy('marca')->orderBy('modelo')->get();

        return view('livewire.automotores.copres.index.search', compact('copres', 'vehiculos'));
    }
}
