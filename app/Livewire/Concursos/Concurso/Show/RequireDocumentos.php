<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Capacitaciones\Documento;
use App\Models\Concursos\DocumentoTipo;
use Livewire\Component;

class RequireDocumentos extends Component
{
    public $open = false;
    public $concurso;
    public $documento_tipos;

    public function mount($concurso) {
        $this->concurso = $concurso;
        $this->documento_tipos = DocumentoTipo::where('de_concurso', false)->orderBy('obligatorio', 'desc')->get();
    }

    public function updateDT($documento_tipo) {
        if($this->concurso->documentos_requeridos->contains($documento_tipo)) {
            if(DocumentoTipo::find($documento_tipo)->obligatorio) {
                // Si el documento es obligatorio, no se puede eliminar
                session()->flash('error', 'No se puede eliminar un documento obligatorio.');
                return;
            }
            $this->concurso->documentos_requeridos()->detach($documento_tipo);
        } else {
            $this->concurso->documentos_requeridos()->attach($documento_tipo);
        }
        // Refresca los subrubros después de la operación
        $this->concurso->refresh();
    }

    public function render()
    {
        return view('livewire.concursos.concurso.show.require-documentos');
    }
}
