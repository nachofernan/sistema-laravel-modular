<?php

namespace App\Livewire\Proveedores\Rubros;

use App\Models\Proveedores\Subrubro;
use Livewire\Component;

class EditSubrubro extends Component
{
    public $subrubro;
    public $nombre;
    public $open = false;

    public function mount(Subrubro $subrubro) {
        $this->subrubro = $subrubro;
        $this->nombre = $subrubro->nombre;
    }

    public function actualizar() {
        $this->subrubro->update(['nombre' => $this->nombre]);
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.proveedores.rubros.edit-subrubro');
    }
}
