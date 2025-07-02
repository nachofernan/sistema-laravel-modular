<?php

namespace App\Livewire\Inventario\Users\Show;

use App\Models\Inventario\Elemento;
use App\Models\Inventario\Estado;
use Livewire\Component;
use Livewire\WithPagination;

class TableSearch extends Component
{
    use WithPagination;
    
    public $user;
    public $estados;
    public $estado_search = array();

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount($user)
    {
        $this->user = $user;
        $this->estados = Estado::all();
        $this->estado_search = [1, 2];
    }

    public function estado_update($estado_id)
    {
        if(in_array($estado_id, $this->estado_search)) {
            unset($this->estado_search[array_search($estado_id, $this->estado_search)]);
        } else {
            $this->estado_search[] = $estado_id;
        }
    }

    public function render()
    {
        $elementos = Elemento::where('user_id', $this->user->id)
                        ->whereIn('estado_id', $this->estado_search)
                        ->paginate(20);

        return view('livewire.inventario.users.show.table-search', compact('elementos'));
    }
}
