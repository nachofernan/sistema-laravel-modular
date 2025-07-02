<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Usuarios\Modulo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class ModuloController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Usuarios/Modulos/Ver', only: ['index', 'show']),
            new Middleware('permission:Usuarios/Modulos/Editar', only: ['edit', 'update']),
            new Middleware('permission:Usuarios/Modulos/Crear', only: ['create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $modulos = Modulo::all();
        return view('usuarios.modulos.index', compact('modulos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('usuarios.modulos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $modulo = Modulo::create($request->all());
        return redirect()->route('usuarios.modulos.show', $modulo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Modulo $modulo)
    {
        //
        return view('usuarios.modulos.show', compact('modulo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modulo $modulo)
    {
        //
        return view('usuarios.modulos.edit', compact('modulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modulo $modulo)
    {
        //
        $modulo->update($request->all());
        return redirect()->route('usuarios.modulos.show', $modulo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modulo $modulo)
    {
        //
    }
}
