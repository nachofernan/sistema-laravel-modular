<?php

namespace App\Livewire\Inventario\Categorias\Caracteristicas;

use App\Models\Inventario\Opcion;
use Livewire\Component;

class Opciones extends Component
{
    public $open = false;
    public $nombre;
    public $caracteristica;

    public function mount($caracteristica)
    {
        $this->caracteristica = $caracteristica;
    }

    public function guardar()
    {
        Opcion::create([
            'nombre' => $this->nombre,
            'caracteristica_id' => $this->caracteristica->id,
        ]);
        return redirect()->route('inventario.categorias.show', $this->caracteristica->categoria);
    }

    public function render()
    {
        return view('livewire.inventario.categorias.caracteristicas.opciones');
    }
}
