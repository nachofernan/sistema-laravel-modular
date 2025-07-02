<?php

namespace App\Livewire\Inventario\Categorias\Caracteristicas;

use Livewire\Component;

class TableRow extends Component
{
    public $caracteristica;
    public $visible;

    public function mount($caracteristica)
    {
        $this->caracteristica = $caracteristica;
        $this->visible = $caracteristica->visible;
    }

    public function render()
    {
        return view('livewire.inventario.categorias.caracteristicas.table-row');
    }
}
