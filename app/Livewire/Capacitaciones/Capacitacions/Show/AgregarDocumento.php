<?php

namespace App\Livewire\Capacitaciones\Capacitacions\Show;

use Livewire\Component;

class AgregarDocumento extends Component
{
    public $open = false;
    public $capacitacion;
    
    public function render()
    {
        return view('livewire.capacitaciones.capacitacions.show.agregar-documento');
    }
}
