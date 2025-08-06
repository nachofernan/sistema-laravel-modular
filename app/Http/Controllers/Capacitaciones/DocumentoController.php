<?php

namespace App\Http\Controllers\Capacitaciones;

use App\Http\Controllers\Controller;
use App\Models\Capacitaciones\Documento;
use App\Models\Capacitaciones\Invitacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
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
        $documento = new Documento([
            'nombre' => $request->input('nombre'),
            'capacitacion_id' => $request->input('capacitacion_id'),
        ]);        
        
        if ($request->hasFile('archivo')) {
            $media = $documento->addMediaFromRequest('archivo')
                ->usingFileName($request->file('archivo')->getClientOriginalName())
                ->toMediaCollection('archivos', 'capacitaciones');
            
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $request->file('archivo')->getClientOriginalName();
            $documento->save();
        } else {
            return redirect()->route('capacitaciones.capacitacions.show', $documento->capacitacion)->with('error', 'No se pudo subir el archivo');
        }
        
        return redirect()->route('capacitaciones.capacitacions.show', $documento->capacitacion);
    }

    /**
     * Display the specified resource.
     */
    /* public function show(Documento $documento)
    {
        //
    } */

    /**
     * Display the specified resource.
     */
    public function show(Documento $documento)
    {
        //
        $user = User::find(Auth::user()->id);
        if(
            $user->hasRole('Capacitaciones/Acceso') ||
            Invitacion::where('capacitacion_id', $documento->capacitacion->id)->where('user_id', $user->id)->count()
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Documento $documento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documento $documento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documento $documento)
    {
        //
        $capacitacion = $documento->capacitacion;
        $documento->delete();
        return redirect()->route('capacitaciones.capacitacions.show', $capacitacion);
    }
}
