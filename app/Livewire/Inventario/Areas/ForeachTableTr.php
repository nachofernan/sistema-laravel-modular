<?php

namespace App\Livewire\Inventario\Areas;

use Livewire\Component;

class ForeachTableTr extends Component
{
    public $areas;
    public $nivel;

    public function render()
    {
        return view('livewire.inventario.areas.foreach-table-tr');
    }
}
