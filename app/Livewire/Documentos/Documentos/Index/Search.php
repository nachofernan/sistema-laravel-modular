<?php

namespace App\Livewire\Documentos\Documentos\Index;

use App\Models\Documentos\Categoria;
use Livewire\Component;

class Search extends Component
{
    public function render()
    {
        //$documentos = Documento::orderBy('categoria_id', 'asc')->orderBy('orden', 'asc')->get();
        $categorias = Categoria::where('categoria_padre_id', '!=', '0')->get();

        return view('livewire.documentos.documentos.index.search', compact('categorias'));
    }
}
