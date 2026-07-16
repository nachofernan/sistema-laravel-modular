<?php

namespace App\Livewire\Usuarios\Areas;

use Livewire\Component;

class ForeachSelect extends Component
{
    public $areas;

    /** Id del área que debe quedar seleccionada (el valor actual). */
    public $selected = null;

    /** Id del área que se está editando: ella y su subárbol se deshabilitan para no elegirlos como padre (evita ciclos). */
    public $excludeId = null;

    /** True cuando ya estamos dentro del subárbol excluido. */
    public bool $dentroExcluido = false;

    public $nivel = '';

    public function render()
    {
        return view('livewire.usuarios.areas.foreach-select');
    }
}
