<?php

namespace App\Livewire\Documentos\Categorias\Show;

use Livewire\Component;

class Edit extends Component
{

    public $open = false;
    public $categoria;
    public $nombre;

    public function mount($categoria)
    {
        $this->categoria = $categoria;
        $this->nombre = $categoria->nombre;
    }

    public function actualizar()
    {
        $this->categoria->nombre = $this->nombre;
        $this->categoria->save();
        return redirect()->route('documentos.categorias.show', $this->categoria);
    }

    public function render()
    {
        return view('livewire.documentos.categorias.show.edit');
    }
}
