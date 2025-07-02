<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Concursos\Concurso;
use Livewire\Component;

class CreateProrroga extends Component
{
    public $open = false;
    public $concurso;

    public function mount(Concurso $concurso) {
        $this->concurso = $concurso;
    }
    
    public function render()
    {
        return view('livewire.concursos.concurso.show.create-prorroga');
    }
}
