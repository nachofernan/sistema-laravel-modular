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

    public function render()
    {
        $sedes = Sede::all();

        $users = User::where(function($query) {
            $query->where('realname', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%');
        })
        ->when($this->sede_id, function($query) {
            return $query->where('sede_id', $this->sede_id);
        })
        ->paginate(20);
            
        return view('livewire.usuarios.users.index.search', compact('users', 'sedes'));
    }
}
