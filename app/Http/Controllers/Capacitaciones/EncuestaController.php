<?php

namespace App\Http\Controllers\Capacitaciones;

use App\Http\Controllers\Controller;
use App\Models\Capacitaciones\Encuesta;
use Illuminate\Http\Request;

class EncuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $encuestas = Encuesta::all();
        return view('capacitaciones.encuestas.index', compact('encuestas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('capacitaciones.encuestas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del request
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'capacitacion_id' => 'required|exists:capacitacions,id',
        ]);

        // Determinar el estado basado en el checkbox
        $estado = $request->has('publicar_inmediatamente') ? '1' : '0';

        // Crear la encuesta con solo los campos necesarios
        $encuesta = Encuesta::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'capacitacion_id' => $request->capacitacion_id,
            'estado' => $estado,
        ]);

        return redirect()->route('capacitaciones.encuestas.show', $encuesta);
    }

    /**
     * Display the specified resource.
     */
    public function show(Encuesta $encuesta)
    {
        //
        return view('capacitaciones.encuestas.show', compact('encuesta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Encuesta $encuesta)
    {
        //
        return view('capacitaciones.encuestas.edit', compact('encuesta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Encuesta $encuesta)
    {
        //
        $encuesta->update($request->all());
        return redirect()->route('capacitaciones.encuestas.show', $encuesta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Encuesta $encuesta)
    {
        //
    }
}
