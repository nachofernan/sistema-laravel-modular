<?php

namespace App\Livewire\Capacitaciones\Encuesta\Show;

use Livewire\Component;

class EliminarPregunta extends Component
{
    public $open = false;
    public $pregunta;
    
    public function render()
    {
        return view('livewire.capacitaciones.encuesta.show.eliminar-pregunta');
    }
}
