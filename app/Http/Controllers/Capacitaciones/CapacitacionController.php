<?php

namespace App\Http\Controllers\Capacitaciones;

use App\Http\Controllers\Controller;
use App\Models\Capacitaciones\Capacitacion;
use Illuminate\Http\Request;

class CapacitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('capacitaciones.capacitacions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('capacitaciones.capacitacions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre de la capacitación es obligatorio.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_final.required' => 'La fecha final es obligatoria.',
            'fecha_final.after_or_equal' => 'La fecha final no puede ser anterior a la fecha de inicio.',
        ]);

        $capacitacion = Capacitacion::create($validated);
        return redirect()->route('capacitaciones.capacitacions.show', $capacitacion);
    }

    /**
     * Display the specified resource.
     */
    public function show(Capacitacion $capacitacion)
    {
        //
        return view('capacitaciones.capacitacions.show', compact('capacitacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Capacitacion $capacitacion)
    {
        //
        return view('capacitaciones.capacitacions.edit', compact('capacitacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Capacitacion $capacitacion)
    {
        //
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre de la capacitación es obligatorio.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_final.required' => 'La fecha final es obligatoria.',
            'fecha_final.after_or_equal' => 'La fecha final no puede ser anterior a la fecha de inicio.',
        ]);

        $capacitacion->update($validated);
        return redirect()->route('capacitaciones.capacitacions.show', $capacitacion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Capacitacion $capacitacion)
    {
        //
    }
}
