<?php

namespace App\Livewire\Usuarios\Areas;

use Livewire\Component;

class ForeachTableTr extends Component
{
    public $areas;
    public int $depth = 0;

    /**
     * Para cada ancestro (nivel ≥ 1) indica si era el último hijo de su nivel.
     * Se usa para dibujar (o no) la línea guía vertical de continuación.
     *
     * @var array<int, bool>
     */
    public array $ancestorsLast = [];

    public function render()
    {
        return view('livewire.usuarios.areas.foreach-table-tr');
    }
}
