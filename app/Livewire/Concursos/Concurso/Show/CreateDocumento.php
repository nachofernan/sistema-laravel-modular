<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Concursos\DocumentoTipo;
use Livewire\Component;

class CreateDocumento extends Component
{
    public $open = false;
    public $concurso;
    public $documento_tipos;

    public function mount($concurso) {
        $this->concurso = $concurso;
        $this->documento_tipos = DocumentoTipo::where('de_concurso', true)->get();
    }

    public function render()
    {
        return view('livewire.concursos.concurso.show.create-documento');
    }
}
