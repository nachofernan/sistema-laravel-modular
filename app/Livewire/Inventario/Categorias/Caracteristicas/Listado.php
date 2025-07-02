<?php

namespace App\Livewire\Inventario\Categorias\Caracteristicas;

use App\Models\Inventario\Caracteristica;
use Livewire\Component;

class Listado extends Component
{
    public $categoria;
    public $caracteristica_nueva = '';

    public function mount($categoria)
    {
        $this->categoria = $categoria;
    }

    public function guardar()
    {
        if($this->caracteristica_nueva) {
            Caracteristica::create([
                'categoria_id' => $this->categoria->id,
                'nombre' => $this->caracteristica_nueva,
            ]);
            $this->caracteristica_nueva = '';
        }
    }

    public function updateCaracteristica($caracteristica_id)
    {
        $caracteristica = Caracteristica::find($caracteristica_id);
        $caracteristica->update([
            'visible' => $caracteristica->visible ? 0 : 1,
        ]);
    }

    public function render()
    {
        return view('livewire.inventario.categorias.caracteristicas.listado');
    }
}
