<?php

namespace App\Livewire\Inventario\Perifericos\Index;

use Livewire\Component;

class Editar extends Component
{
    public $open = false;
    public $periferico;

    public function render()
    {
        return view('livewire.inventario.perifericos.index.editar');
    }
}
