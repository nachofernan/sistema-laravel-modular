<?php

namespace App\Livewire\Usuarios\Areas;

use Livewire\Component;

class ForeachTableTr extends Component
{
    public $areas;
    public $nivel;

    public function render()
    {
        return view('livewire.usuarios.areas.foreach-table-tr');
    }
}
