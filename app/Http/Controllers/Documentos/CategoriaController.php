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
            new Middleware('permission:Documentos/Categorias/Editar', only: ['edit', 'update']),
            new Middleware('permission:Documentos/Categorias/Crear', only: ['create', 'store']),
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categorias = Categoria::whereNull('categoria_padre_id')->get();
        return view('documentos.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categorias = Categoria::whereNull('categoria_padre_id')->get();
        return view('documentos.categorias.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = [
            'nombre' => $request->input('nombre'), 
            'categoria_padre_id' => is_numeric($request->input('categoria_id')) ? $request->input('categoria_id') : null, 
        ];
        $categoria = Categoria::create($data);
        return redirect()->route('documentos.categorias.show', $categoria);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
        return view('documentos.categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
