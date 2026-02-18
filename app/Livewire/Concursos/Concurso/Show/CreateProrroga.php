<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Concursos\Concurso;
use Livewire\Component;

class CreateProrroga extends Component
{
    public $open = false;
    public $concurso;
    public $fecha_cierre;

    public function mount(Concurso $concurso) {
        $this->concurso = $concurso;
    }

    // Se ejecuta cada vez que 'fecha_cierre' cambia en el input
    public function updatedFechaCierre($value)
    {
        $this->validate([
            'fecha_cierre' => 'after:' . $this->concurso->fecha_cierre,
        ], [
            'fecha_cierre.after' => 'La fecha debe ser posterior al cierre actual (' . $this->concurso->fecha_cierre->format('d/m/Y H:i') . ').'
        ]);
    }
    
    public function render()
    {
        return view('livewire.concursos.concurso.show.create-prorroga');
    }
}
