<?php

namespace App\Livewire\Inventario\Categorias\Caracteristicas;

use App\Models\Inventario\Caracteristica;
use Livewire\Component;

class Create extends Component
{
    public $open = false;
    public $nombre;
    public $opciones;
    public $categoria;

    public function mount($categoria)
    {
        $this->categoria = $categoria;
    }

    public function guardar()
    {
        Caracteristica::create([
            'nombre' => $this->nombre,
            'con_opciones' => $this->opciones ?? 0,
            'categoria_id' => $this->categoria->id,
        ]);
        return redirect()->route('inventario.categorias.show', $this->categoria);
    }

    public function render()
    {
        return view('livewire.inventario.categorias.caracteristicas.create');
    }
}
