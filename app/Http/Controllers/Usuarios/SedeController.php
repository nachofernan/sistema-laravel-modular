<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuarios\Sede;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;

class SedeController extends Controller
{
    
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Usuarios/Sedes/Ver', only: ['index', 'show']),
            new Middleware('permission:Usuarios/Sedes/Editar', only: ['edit', 'update']),
            new Middleware('permission:Usuarios/Sedes/Crear', only: ['create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sedes = Sede::all();
        return view('usuarios.sedes.index', compact('sedes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('usuarios.sedes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $sede = Sede::create($request->all());
        return redirect()->route('usuarios.sedes.edit', $sede);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sede $sede)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sede $sede)
    {
        //
        return view('usuarios.sedes.edit', compact('sede'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sede $sede)
    {
        //
        $sede->update($request->all());
        return redirect()->route('usuarios.sedes.edit', $sede);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sede $sede)
    {
        //
    }
}
