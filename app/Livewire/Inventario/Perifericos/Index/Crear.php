<?php

namespace App\Livewire\Inventario\Perifericos\Index;

use Livewire\Component;

class Crear extends Component
{
    public $open = false;

    public function render()
    {
        return view('livewire.inventario.perifericos.index.crear');
    }
}
