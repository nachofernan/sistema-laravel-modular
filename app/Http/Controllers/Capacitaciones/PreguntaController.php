<?php

namespace App\Http\Controllers\Capacitaciones;

use App\Http\Controllers\Controller;
use App\Models\Capacitaciones\Pregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
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
        //
        $pregunta = Pregunta::create($request->all());
        return redirect()->route('capacitaciones.encuestas.show', [$pregunta->encuesta]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pregunta $pregunta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pregunta $pregunta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pregunta $pregunta)
    {
        //
        $pregunta->update($request->all());
        return redirect()->route('capacitaciones.encuestas.show', [$pregunta->encuesta]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pregunta $pregunta)
    {
        //
        $encuesta = $pregunta->encuesta;
        $pregunta->delete();
        return redirect()->route('capacitaciones.encuestas.show', $encuesta);
    }
}
