<?php

namespace App\Livewire\Automotores\Vehiculos\Index;

use App\Models\Automotores\Vehiculo;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search = '';
    public $marca_filter = '';
    public $modelo_filter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'marca_filter' => ['except' => ''],
        'modelo_filter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMarcaFilter()
    {
        $this->resetPage();
    }

    public function updatingModeloFilter()
    {
        $this->resetPage();
    }

    public function delete($vehiculoId)
    {
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        
        // Verificar si tiene COPRES asociadas
        if ($vehiculo->copres()->count() > 0) {
            session()->flash('error', 'No se puede eliminar el vehículo porque tiene COPRES asociadas');
            return;
        }
        
        $vehiculo->delete();
        session()->flash('info', 'Vehículo eliminado exitosamente');
    }

    public function render()
    {
        $vehiculos = Vehiculo::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('marca', 'like', '%' . $this->search . '%')
                      ->orWhere('modelo', 'like', '%' . $this->search . '%')
                      ->orWhere('patente', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->marca_filter, function ($query) {
                $query->where('marca', $this->marca_filter);
            })
            ->when($this->modelo_filter, function ($query) {
                $query->where('modelo', 'like', '%' . $this->modelo_filter . '%');
            })
            ->orderBy('marca')
            ->orderBy('modelo')
            ->paginate(15);

        $marcas = Vehiculo::distinct()->pluck('marca')->sort()->values();

        return view('livewire.automotores.vehiculos.index.search', compact('vehiculos', 'marcas'));
    }
}
