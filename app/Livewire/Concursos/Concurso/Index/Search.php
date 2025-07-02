<?php

namespace App\Livewire\Concursos\Concurso\Index;

use App\Models\Concursos\Concurso;
use App\Models\Concursos\Estado;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;
    
    public $search = '';
    public $estados;
    public $estado_search = array();


    public function mount()
    {
        $this->estados = Estado::all();
        /* foreach ($this->estados as $estado) {
            $this->estado_search[] = $estado->id;
        } */
       $this->estado_search = [1, 2, 3];
    
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
        if($this->search != '') {
            $concursos = Concurso::where('nombre', 'like', '%'.$this->search.'%');
        } else {
            $concursos = Concurso::whereIn('estado_id', $this->estado_search);
        }

        $concursos = $concursos->paginate(20);

        return view('livewire.concursos.concurso.index.search', compact('concursos'));
    }
}
