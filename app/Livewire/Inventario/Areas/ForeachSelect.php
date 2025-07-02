<?php

namespace App\Livewire\Inventario\Areas;

use Livewire\Component;

class ForeachSelect extends Component
{
    public $areas;
    public $area_id;
    public $disabled;
    public $nivel;

    public function render()
    {
        return view('livewire.inventario.areas.foreach-select');
    }
}
