<?php

namespace App\Livewire\Fichadas\Fichadas\Index;

use App\Models\Fichadas\Fichada;
use Livewire\Component;

class Search extends Component
{
    public $search = '';

    public function render()
    {
        if($this->search != '') {
            $fichadas = Fichada::where('fecha', $this->search)->orderBy('idEmpleado')->orderBy('hora')->get()->groupBy('idEmpleado');
        } else {
            $fichadas = Fichada::where('fecha', now()->format('Y-m-d'))->orderBy('idEmpleado')->orderBy('hora')->get()->groupBy('idEmpleado');
        }
        return view('livewire.fichadas.fichadas.index.search', compact('fichadas'));
    }
}
