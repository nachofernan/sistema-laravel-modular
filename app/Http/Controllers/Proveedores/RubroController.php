<?php

namespace App\Http\Controllers\Proveedores;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\Rubro;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class RubroController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Proveedores/Rubros/Ver', only: ['index', 'show', 'export']),
            new Middleware('permission:Proveedores/Rubros/Editar', only: ['edit', 'update', 'store', 'create']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rubros = Rubro::all();
        return view('proveedores.rubros.index', compact('rubros'));
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
    }

    /**
     * Display the specified resource.
     */
    public function show(Rubro $rubro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rubro $rubro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rubro $rubro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubro $rubro)
    {
        //
    }
}
