<?php

namespace App\Livewire\Proveedores\Rubros;

use App\Models\Proveedores\Rubro;
use App\Models\Proveedores\Subrubro;
use Livewire\Component;

class CreateSubrubro extends Component
{

    public $open = false;
    public $nombre;
    public $rubro;

    public function mount(Rubro $rubro) {
        $this->rubro = $rubro;
    }

    public function crear() {
        Subrubro::create(['rubro_id' => $this->rubro->id, 'nombre' => $this->nombre]);
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.proveedores.rubros.create-subrubro');
    }
}
