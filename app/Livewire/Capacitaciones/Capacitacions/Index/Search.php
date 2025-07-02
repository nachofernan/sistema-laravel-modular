<?php

namespace App\Livewire\Capacitaciones\Capacitacions\Index;

use App\Models\Capacitaciones\Capacitacion;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        $capacitacions = Capacitacion::all();

        return view('livewire.capacitaciones.capacitacions.index.search', compact('capacitacions'));
    }
}
