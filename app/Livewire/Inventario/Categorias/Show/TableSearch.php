<?php

namespace App\Livewire\Inventario\Categorias\Show;

use App\Models\Inventario\Elemento;
use App\Models\Inventario\Estado;
use Livewire\Component;
use Livewire\WithPagination;

class TableSearch extends Component
{
    
    use WithPagination;
    
    public $search = '';
    public $categoria;
    public $estados;
    public $estado_search = array();

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount($categoria)
    {
        $this->categoria = $categoria;
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
        $elementos = Elemento::where('codigo', 'like', '%'.$this->search.'%')
                        ->where('categoria_id', $this->categoria->id)
                        ->whereIn('estado_id', $this->estado_search)
                        ->paginate(20);

        return view('livewire.inventario.categorias.show.table-search', compact('elementos'));
    }
}
