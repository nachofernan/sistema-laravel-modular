<?php

namespace App\Livewire\Documentos\Documentos\Index;

use App\Models\Documentos\Categoria;
use Livewire\Attributes\Url;
use Livewire\Component;

class Search extends Component
{
    /** Queda en la URL para poder compartir o recargar una búsqueda. */
    #[Url(as: 'q', except: '')]
    public string $buscar = '';

    public function limpiar(): void
    {
        $this->buscar = '';
    }

    public function render()
    {
        $termino = trim($this->buscar);

        $categorias = Categoria::subcategorias()
            ->with(['padre', 'documentos' => fn ($query) => $query
                ->when($termino !== '', fn ($query) => $query->buscar($termino))
                ->withCount('descargas')
                ->orderBy('orden')
                ->orderBy('nombre'),
            ])
            ->get();

        // Buscando, una categoría sin resultados es ruido; sin término, se listan
        // todas para que se vea la estructura completa aunque estén vacías.
        if ($termino !== '') {
            $categorias = $categorias->filter(fn ($categoria) => $categoria->documentos->isNotEmpty());
        }

        return view('livewire.documentos.documentos.index.search', [
            'categorias' => $categorias,
            'termino' => $termino,
            'resultados' => $categorias->sum(fn ($categoria) => $categoria->documentos->count()),
        ]);
    }
}
