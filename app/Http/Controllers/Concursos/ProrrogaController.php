<?php

namespace App\Http\Controllers\Concursos;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use App\Mail\Concursos\NuevaProrroga;
use App\Models\Concursos\Concurso;
use App\Models\Concursos\Prorroga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProrrogaController extends Controller
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
        $concurso = Concurso::find($request->input('concurso_id'));
        $prorroga = Prorroga::create([
            'fecha_anterior' => $concurso->fecha_cierre,
            'fecha_actual' => $request->input('fecha_cierre'),
            'concurso_id' => $concurso->id,
        ]);
        $concurso->fecha_cierre = $request->input('fecha_cierre');
        $concurso->save();

        // Obtener correos de proveedores invitados, contactos de proveedores y contactos de concurso
        $correos = $concurso->getCorreosInteresados(['proveedores', 'contactos_concurso', 'contactos_proveedores']);

        EmailHelper::reprogramarEmailsProrroga($prorroga, $correos);

        return redirect()->route('concursos.concursos.show', $concurso);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
