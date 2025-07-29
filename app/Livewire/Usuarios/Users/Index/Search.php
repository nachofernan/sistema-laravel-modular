<?php

namespace App\Livewire\Usuarios\Users\Index;

use App\Models\Usuarios\Sede;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sede_id = 0;

    // Resetear paginación cuando cambian los filtros
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSede_id()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sedes = Sede::orderBy('nombre')->get();

        $users = User::query()
            ->where(function($query) {
                if (!empty($this->search)) {
                    $query->where('realname', 'like', '%' . $this->search . '%')
                          ->orWhere('name', 'like', '%' . $this->search . '%')
                          ->orWhere('legajo', 'like', '%' . $this->search . '%');
                }
            })
            ->when($this->sede_id, function($query) {
                return $query->where('sede_id', $this->sede_id);
            })
            ->orderBy('realname')
            ->paginate(20);
            
        return view('livewire.usuarios.users.index.search', compact('users', 'sedes'));
    }
}