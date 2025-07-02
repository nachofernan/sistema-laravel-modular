<?php

namespace App\Livewire\Capacitaciones\Encuesta\Show;

use Livewire\Component;

class EditarEncuesta extends Component
{
    public $open = false;
    public $encuesta;

    public function render()
    {
        return view('livewire.capacitaciones.encuesta.show.editar-encuesta');
    }
}
