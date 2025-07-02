<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Capacitaciones\Encuesta;
use App\Models\Capacitaciones\Pregunta;
use App\Models\Capacitaciones\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncuestaController extends Controller
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
        foreach($request->all() as $key => $respuesta_valor) {
            if(is_numeric($key)) {
                $pregunta = Pregunta::find($key);
                $respuesta = new Respuesta ([
                    'user_id' => Auth::user()->id,
                    'pregunta_id' => $pregunta->id,
                ]);
                if($pregunta->con_opciones) {
                    $respuesta->opcion_id = $respuesta_valor;
                } else {
                    $respuesta->respuesta = $respuesta_valor;
                }
                $respuesta->save();
            }
        }
        return redirect()->route('home.capacitacions.show', $pregunta->encuesta->capacitacion)->with('msg', 'Enviado');;
    }

    /**
     * Display the specified resource.
     */
    public function show(Encuesta $encuesta)
    {
        //
        return view('home.encuestas.show', compact('encuesta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Encuesta $encuesta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Encuesta $encuesta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Encuesta $encuesta)
    {
        //
    }
}
