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
        //
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
