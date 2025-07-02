<?php

namespace App\Livewire\Usuarios\Areas;

use Livewire\Component;

class ForeachSelect extends Component
{
    public $areas;
    public $area_id;
    public $disabled;
    public $nivel;

    public function render()
    {
        return view('livewire.usuarios.areas.foreach-select');
    }
}
