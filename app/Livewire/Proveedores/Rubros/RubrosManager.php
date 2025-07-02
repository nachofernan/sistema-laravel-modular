<?php

namespace App\Livewire\Proveedores\Rubros;

use App\Models\Proveedores\Rubro;
use App\Models\Proveedores\Subrubro;
use Livewire\Component;

class RubrosManager extends Component
{
    public $rubros;
    public $search = '';
    
    // Modal states
    public $modalOpen = false;
    public $modalType = ''; // 'create_rubro', 'edit_rubro', 'create_subrubro', 'edit_subrubro', 'delete_rubro', 'delete_subrubro'
    public $modalTitle = '';
    
    // Form data
    public $nombre = '';
    public $currentRubro = null;
    public $currentSubrubro = null;
    
    // Expandir/contraer rubros
    public $expandedRubros = [];

    public function mount()
    {
        $this->loadRubros();
    }

    public function loadRubros()
    {
        if (strlen($this->search) > 2) {
            // Buscar rubros y subrubros que coincidan
            $this->rubros = Rubro::with('subrubros')
                ->where('nombre', 'LIKE', '%' . $this->search . '%')
                ->orWhereHas('subrubros', function($query) {
                    $query->where('nombre', 'LIKE', '%' . $this->search . '%');
                })
                ->orderBy('nombre')
                ->get();
            
            // Expandir todos si hay búsqueda
            $this->expandedRubros = $this->rubros->pluck('id')->toArray();
        } else {
            $this->rubros = Rubro::with('subrubros')->orderBy('nombre')->get();
        }
    }

    public function updatedSearch()
    {
        $this->loadRubros();
    }

    public function toggleRubro($rubroId)
    {
        if (in_array($rubroId, $this->expandedRubros)) {
            $this->expandedRubros = array_diff($this->expandedRubros, [$rubroId]);
        } else {
            $this->expandedRubros[] = $rubroId;
        }
    }

    // Modal methods
    public function openCreateRubro()
    {
        $this->resetModal();
        $this->modalType = 'create_rubro';
        $this->modalTitle = 'Crear Nuevo Rubro';
        $this->modalOpen = true;
    }

    public function openEditRubro($rubroId)
    {
        $this->resetModal();
        $this->currentRubro = Rubro::find($rubroId);
        $this->nombre = $this->currentRubro->nombre;
        $this->modalType = 'edit_rubro';
        $this->modalTitle = 'Editar Rubro';
        $this->modalOpen = true;
    }

    public function openCreateSubrubro($rubroId)
    {
        $this->resetModal();
        $this->currentRubro = Rubro::find($rubroId);
        $this->modalType = 'create_subrubro';
        $this->modalTitle = 'Crear Subrubro en: ' . $this->currentRubro->nombre;
        $this->modalOpen = true;
    }

    public function openEditSubrubro($subrubroId)
    {
        $this->resetModal();
        $this->currentSubrubro = Subrubro::with('rubro')->find($subrubroId);
        $this->nombre = $this->currentSubrubro->nombre;
        $this->modalType = 'edit_subrubro';
        $this->modalTitle = 'Editar Subrubro';
        $this->modalOpen = true;
    }

    public function openDeleteRubro($rubroId)
    {
        $this->resetModal();
        $this->currentRubro = Rubro::with('subrubros')->find($rubroId);
        $this->modalType = 'delete_rubro';
        $this->modalTitle = 'Eliminar Rubro';
        $this->modalOpen = true;
    }

    public function openDeleteSubrubro($subrubroId)
    {
        $this->resetModal();
        $this->currentSubrubro = Subrubro::with('rubro')->find($subrubroId);
        $this->modalType = 'delete_subrubro';
        $this->modalTitle = 'Eliminar Subrubro';
        $this->modalOpen = true;
    }

    // CRUD operations
    public function createRubro()
    {
        $this->validate(['nombre' => 'required|min:2']);
        
        Rubro::create(['nombre' => $this->nombre]);
        $this->loadRubros();
        $this->closeModal();
        
        session()->flash('message', 'Rubro creado exitosamente');
    }

    public function updateRubro()
    {
        $this->validate(['nombre' => 'required|min:2']);
        
        $this->currentRubro->update(['nombre' => $this->nombre]);
        $this->loadRubros();
        $this->closeModal();
        
        session()->flash('message', 'Rubro actualizado exitosamente');
    }

    public function createSubrubro()
    {
        $this->validate(['nombre' => 'required|min:2']);
        
        Subrubro::create([
            'rubro_id' => $this->currentRubro->id,
            'nombre' => $this->nombre
        ]);
        
        $this->loadRubros();
        $this->closeModal();
        
        // Expandir el rubro para mostrar el nuevo subrubro
        if (!in_array($this->currentRubro->id, $this->expandedRubros)) {
            $this->expandedRubros[] = $this->currentRubro->id;
        }
        
        session()->flash('message', 'Subrubro creado exitosamente');
    }

    public function updateSubrubro()
    {
        $this->validate(['nombre' => 'required|min:2']);
        
        $this->currentSubrubro->update(['nombre' => $this->nombre]);
        $this->loadRubros();
        $this->closeModal();
        
        session()->flash('message', 'Subrubro actualizado exitosamente');
    }

    public function deleteRubro()
    {
        // Verificar si tiene subrubros
        if ($this->currentRubro->subrubros->count() > 0) {
            session()->flash('error', 'No se puede eliminar un rubro que tiene subrubros');
            $this->closeModal();
            return;
        }

        $this->currentRubro->delete();
        $this->loadRubros();
        $this->closeModal();
        
        session()->flash('message', 'Rubro eliminado exitosamente');
    }

    public function deleteSubrubro()
    {
        $this->currentSubrubro->delete();
        $this->loadRubros();
        $this->closeModal();
        
        session()->flash('message', 'Subrubro eliminado exitosamente');
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->resetModal();
    }

    private function resetModal()
    {
        $this->modalType = '';
        $this->modalTitle = '';
        $this->nombre = '';
        $this->currentRubro = null;
        $this->currentSubrubro = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.proveedores.rubros.rubros-manager');
    }
}