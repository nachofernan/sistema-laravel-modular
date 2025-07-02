<?php

namespace App\Http\Controllers\Proveedores;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\Documento;
use App\Models\Proveedores\Validacion;
use Illuminate\Http\Request;

class ValidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        foreach (Documento::WhereDoesntHave('validacion')->get() as $documento) {
            $documento->validacion()->create();
        }
        $validaciones = Validacion::where('validado', false)->where('comentarios', null)->get();
        return view('proveedores.validacions.index', compact('validaciones'));
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
    public function show(Validacion $validacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Validacion $validacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Validacion $validacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Validacion $validacion)
    {
        //
    }
}
