<?php

namespace App\Livewire\Documentos\Documentos\Index;

use App\Models\Documentos\Categoria;
use Livewire\Component;

class Search extends Component
{
    public function render()
    {
        $categorias = Categoria::subcategorias()
            ->with(['padre', 'documentos' => fn ($query) => $query
                ->withCount('descargas')
                ->orderBy('orden')
                ->orderBy('nombre'),
            ])
            ->withCount('documentos')
            ->get();

        return view('livewire.documentos.documentos.index.search', compact('categorias'));
    }
}
