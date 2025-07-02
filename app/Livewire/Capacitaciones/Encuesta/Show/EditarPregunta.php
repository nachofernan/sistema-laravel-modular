<?php

namespace App\Livewire\Capacitaciones\Encuesta\Show;

use Livewire\Component;

class EditarPregunta extends Component
{
    public $open = false;
    public $pregunta;

    public function render()
    {
        return view('livewire.capacitaciones.encuesta.show.editar-pregunta');
    }
}
