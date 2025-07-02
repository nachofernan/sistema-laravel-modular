<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\Periferico;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class PerifericoController extends Controller
{
    
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Inventario/Perifericos/Ver', only: ['index', 'show']),
            new Middleware('permission:Inventario/Perifericos/Editar', only: ['edit', 'update']),
            new Middleware('permission:Inventario/Perifericos/Crear', only: ['create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $perifericos = Periferico::all();
        return view('inventario.perifericos.index', compact('perifericos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        Periferico::create($request->all());
        return redirect()->route('inventario.perifericos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Periferico $periferico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periferico $periferico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Periferico $periferico)
    {
        //
        $periferico->update($request->all());
        return redirect()->route('inventario.perifericos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periferico $periferico)
    {
        //
    }
}
