<?php

namespace App\Livewire\Inventario\Elementos\Index;

use App\Models\Inventario\Categoria;
use App\Models\Inventario\Elemento;
use App\Models\Inventario\Estado;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{

    use WithPagination;
    
    public $search = '';
    public $estados;
    public $estado_search = array();
    public $categorias;
    public $categoria;


    public function mount()
    {
        $this->estados = Estado::all();
        $this->estado_search = [1, 2];

        $this->categorias = Categoria::all();
        $this->categoria = 0;
    }

    public function estado_update($estado_id)
    {
        if(in_array($estado_id, $this->estado_search)) {
            unset($this->estado_search[array_search($estado_id, $this->estado_search)]);
        } else {
            $this->estado_search[] = $estado_id;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $elementos = Elemento::where('codigo', 'like', '%'.$this->search.'%')
                        ->whereIn('estado_id', $this->estado_search);
        if($this->categoria) {
            $elementos = $elementos->where('categoria_id', $this->categoria);
        }
        $elementos = $elementos->limit(100)->paginate(20);

        return view('livewire.inventario.elementos.index.search', compact('elementos'));
    }
}
