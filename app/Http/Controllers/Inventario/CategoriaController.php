<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\Categoria;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class CategoriaController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Inventario/Categorias/Ver', only: ['index', 'show']),
            new Middleware('permission:Inventario/Categorias/Editar', only: ['edit', 'update']),
            new Middleware('permission:Inventario/Categorias/Crear', only: ['create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categorias = Categoria::all();
        return view('inventario.categorias.index', compact('categorias')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('inventario.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'prefijo' => 'required|string|max:10|unique:categorias,prefijo',
        ]);

        $categoria = Categoria::create($request->all());
        return redirect()->route('inventario.categorias.show', $categoria);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
        $categoria = $categoria;
        return view('inventario.categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('inventario.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'prefijo' => 'required|string|max:10|unique:categorias,prefijo,' . $categoria->id,
        ]);

        $categoria->update($request->all());
        return redirect()->route('inventario.categorias.show', $categoria);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
