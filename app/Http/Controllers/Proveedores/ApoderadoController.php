<?php

namespace App\Http\Controllers\Proveedores;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use App\Mail\Proveedores\NuevoArchivoValidacion;
use App\Models\Proveedores\Apoderado;
use App\Models\Proveedores\DocumentoApoderado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApoderadoController extends Controller
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
        $request->validate([
            'proveedor_id' => 'required',
            'file' => 'required',
            'tipo' => 'required',
        ]);
        $apoderado = Apoderado::create([
            'proveedor_id' => $request->input('proveedor_id'),
            'nombre' => $request->input('nombre') ?? null,
            'tipo' => $request->input('tipo'),
        ]);

        if($request->file('file')) {
            $documento = $apoderado->documentos()->create([
                'user_id_created' => Auth::user()->id,
                'vencimiento' => $request->all('vencimiento')['vencimiento'] ?? null,
            ]);

            $media = $documento->addMediaFromRequest('file')
                ->usingFileName($request->file('file')->getClientOriginalName())
                ->toMediaCollection('archivos');
            
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $media->getPath();
            $documento->save();

            $documento->validacion()->create();
            
            //Mail::to(['egaitero@buenosairesenergia.com.ar', 'jprojeda@buenosairesenergia.com.ar', 'mmartin@buenosairesenergia.com.ar'])->send(new NuevoArchivoValidacion($documento->validacion));
            EmailHelper::enviarNotificacion(
                ['egaitero@buenosairesenergia.com.ar', /* 'jprojeda@buenosairesenergia.com.ar', 'mmartin@buenosairesenergia.com.ar' */],
                new NuevoArchivoValidacion($documento->validacion),
                'Nuevo archivo para validación del proveedor ' . $apoderado->proveedor->razonsocial
            );
        }

        return redirect()->route('proveedores.proveedors.show', $apoderado->proveedor)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Apoderado $apoderado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apoderado $apoderado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Apoderado $apoderado)
    {
        //
        $apoderado->update([
            'activo' => $request->input('activo') ? true : false,
            'nombre' => $request->input('nombre') ?? null,
        ]);
        if($request->file('file')) {
            $documento = $apoderado->documentos()->create([
                'user_id_created' => Auth::user()->id,
                'vencimiento' => $request->all('vencimiento')['vencimiento'] ?? null,
            ]);

            $media = $documento->addMediaFromRequest('file')
                ->usingFileName($request->file('file')->getClientOriginalName())
                ->toMediaCollection('archivos');
            
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $media->getPath();
            $documento->save();

            $documento->validacion()->create();
            
            //Mail::to('ifernandez@buenosairesenergia.com.ar')->send(new NuevoArchivoValidacion($documento->validacion));
            EmailHelper::enviarNotificacion(
                ['egaitero@buenosairesenergia.com.ar', /* 'jprojeda@buenosairesenergia.com.ar', 'mmartin@buenosairesenergia.com.ar' */],
                new NuevoArchivoValidacion($documento->validacion),
                'Nuevo archivo de validación para el proveedor ' . $apoderado->proveedor->razon_social
            );
        }

        return redirect()->route('proveedores.proveedors.show', $apoderado->proveedor)->with('info', 'Se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apoderado $apoderado)
    {
        //
        $proveedor = $apoderado->proveedor;
        $apoderado->delete();
        return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se eliminó con éxito');
    }
}
