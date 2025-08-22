<?php

namespace App\Http\Controllers\Capacitaciones;

use App\Http\Controllers\Controller;
use App\Models\Capacitaciones\Pregunta;
use App\Models\Capacitaciones\Opcion;
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
        // Validar los datos básicos
        $request->validate([
            'pregunta' => 'required|string',
            'encuesta_id' => 'required',
            'tipo_pregunta' => 'required|in:opcion_multiple,texto_libre'
        ]);

        // Determinar si la pregunta tiene opciones
        $con_opciones = $request->tipo_pregunta === 'opcion_multiple';

        // Crear la pregunta
        $pregunta = Pregunta::create([
            'pregunta' => $request->pregunta,
            'encuesta_id' => $request->encuesta_id,
            'con_opciones' => $con_opciones
        ]);

        // Solo crear opciones si es de opción múltiple Y hay opciones válidas
        if ($con_opciones && $request->has('opciones') && is_array($request->opciones)) {
            foreach ($request->opciones as $opcionTexto) {
                if (!empty(trim($opcionTexto))) {
                    $pregunta->opciones()->create([
                        'opcion' => trim($opcionTexto)
                    ]);
                }
            }
        }

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
        // Validar los datos
        $request->validate([
            'pregunta' => 'required|string'
        ]);

        // Actualizar solo el texto de la pregunta
        $pregunta->update([
            'pregunta' => $request->pregunta
        ]);
        
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
