<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use App\Models\Documentos\Categoria;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class CategoriaController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Documentos/Categorias/Ver', only: ['index', 'show']),
            new Middleware('permission:Documentos/Categorias/Crear', only: ['create', 'store']),
        ];
    }

    public function index()
    {
        $categorias = Categoria::whereNull('categoria_padre_id')
            ->with('hijos')
            ->orderBy('orden')
            ->get();

        return view('documentos.categorias.index', compact('categorias'));
    }

    public function create()
    {
        $categorias = Categoria::whereNull('categoria_padre_id')->orderBy('orden')->get();

        return view('documentos.categorias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $categoria = Categoria::create($this->validar($request));

        return redirect()->route('documentos.categorias.show', $categoria);
    }

    public function show(Categoria $categoria)
    {
        $categoria->load('hijos', 'documentos');

        return view('documentos.categorias.show', compact('categoria'));
    }

    /**
     * La edición de una categoría vive en el componente Livewire
     * `documentos.categorias.show.edit`, embebido en el listado.
     */
    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'categoria_padre_id' => ['nullable', 'exists:documentos.categorias,id'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'categoria_padre_id.exists' => 'La categoría padre elegida no existe.',
        ]);

        $datos['publica'] = $request->boolean('publica');
        $datos['orden'] = $datos['orden'] ?? 0;

        return $datos;
    }
}
