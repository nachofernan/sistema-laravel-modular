<?php

namespace App\Livewire\Proveedores\Rubros;

use App\Models\Proveedores\Rubro;
use Livewire\Component;

class EditRubro extends Component
{
    public $rubro;
    public $nombre;
    public $open = false;

    public function mount(Rubro $rubro) {
        $this->rubro = $rubro;
        $this->nombre = $rubro->nombre;
    }

    public function actualizar() {
        $this->rubro->update(['nombre' => $this->nombre]);
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.proveedores.rubros.edit-rubro');
    }
}
