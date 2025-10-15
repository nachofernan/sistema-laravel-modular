<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Capacitaciones\Capacitacion;
use App\Models\Capacitaciones\Documento;
use App\Models\Capacitaciones\Invitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CapacitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('home.capacitacions.index');
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
    public function show(Capacitacion $capacitacion)
    {
        //
        $invitacion = Invitacion::where('capacitacion_id', $capacitacion->id)->where('user_id', Auth::user()->id)->first();
        return view('home.capacitacions.show', compact('capacitacion', 'invitacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Capacitacion $capacitacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Capacitacion $capacitacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Capacitacion $capacitacion)
    {
        //
    }

    public function documentoDownload(Capacitacion $capacitacion, Documento $documento)
    {
        //
        if(
            Invitacion::where('capacitacion_id', $documento->capacitacion->id)->where('user_id', Auth::user()->id)->count()
        ) {
            $media = $documento->getFirstMedia('archivos');
            if ($media) {
                return $media->toResponse(request());
            }
            abort(404, 'Archivo no encontrado.');
        }
        else
        {
            return "No tiene los permisos";
        }
    }
}
