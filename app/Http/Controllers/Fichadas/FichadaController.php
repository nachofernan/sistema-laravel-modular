<?php

namespace App\Http\Controllers\Fichadas;

use App\Http\Controllers\Controller;
use App\Models\Fichadas\Fichada;
use Illuminate\Http\Request;

class FichadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $fichadas = Fichada::where('fecha', now()->format('Y-m-d'))->orderBy('idEmpleado')->orderBy('hora')->get()->groupBy('idEmpleado');
        return view('fichadas.fichadas.index', compact('fichadas'));
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
    public function show(Fichada $fichada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fichada $fichada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fichada $fichada)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fichada $fichada)
    {
        //
    }
}
