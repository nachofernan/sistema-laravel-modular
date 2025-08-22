<?php

namespace App\Http\Controllers\Capacitaciones;

use App\Http\Controllers\Controller;
use App\Models\Capacitaciones\Opcion;
use Illuminate\Http\Request;

class OpcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Validar los datos
        $request->validate([
            'opcion' => 'required|string|max:255',
            'pregunta_id' => 'required'
        ]);

        $opcion = Opcion::create([
            'opcion' => $request->opcion,
            'pregunta_id' => $request->pregunta_id
        ]);
        
        return redirect()->route('capacitaciones.encuestas.show', $opcion->pregunta->encuesta);
    }

    /**
     * Display the specified resource.
     */
    public function show(Opcion $opcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Opcion $opcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Opcion $opcion)
    {
        // Validar los datos
        $request->validate([
            'opcion' => 'required|string|max:255'
        ]);

        $opcion->update([
            'opcion' => $request->opcion
        ]);
        
        return redirect()->route('capacitaciones.encuestas.show', $opcion->pregunta->encuesta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Opcion $opcion)
    {
        //
        $pregunta = $opcion->pregunta;
        $opcion->delete();
        return redirect()->route('capacitaciones.encuestas.show', $pregunta->encuesta);
    }
}
