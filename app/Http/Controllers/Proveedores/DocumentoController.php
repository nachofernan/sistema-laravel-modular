<?php

namespace App\Http\Controllers\Proveedores;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use App\Mail\Proveedores\NuevoArchivoValidacion;
use App\Models\Proveedores\Documento;
use App\Models\Proveedores\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
        $proveedor = Proveedor::find($request->input('proveedor_id'));

        $documento = $proveedor->documentos()->create([
            'archivo' => 'x',
            'file_storage' => 'x',
            'extension' => 'x',
            'mimeType' => 'x',
            'user_id_created' => Auth::user()->id,
            'vencimiento' => $request->input('vencimiento') ?? null,
            'documento_tipo_id' => $request->input('documento_tipo_id'),
        ]);

        if ($request->hasFile('file')) {
            $media = $documento->addMediaFromRequest('file')
                ->usingFileName($request->file('file')->getClientOriginalName())
                ->toMediaCollection('archivos', 'proveedores');
            
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $request->file('file')->getClientOriginalName();
            $documento->save();
        }

        $documento->validacion()->create();
        
        //Mail::to(['egaitero@buenosairesenergia.com.ar', 'jprojeda@buenosairesenergia.com.ar', 'mmartin@buenosairesenergia.com.ar'])->send(new NuevoArchivoValidacion($documento->validacion));
        EmailHelper::enviarNotificacion(
            ['egaitero@buenosairesenergia.com.ar', /* 'jprojeda@buenosairesenergia.com.ar', 'mmartin@buenosairesenergia.com.ar' */],
            new NuevoArchivoValidacion($documento->validacion),
            'Nuevo archivo para validación del proveedor ' . $proveedor->razonsocial
        );

        return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Documento $documento)
    {
        //
        $media = $documento->getFirstMedia('archivos');
            if ($media) {
                return $media->toResponse(request());
            }
            abort(404, 'Archivo no encontrado.');
    }

    /**
     * Download the specified resource.
     */
    public function download(Documento $documento)
    {
        $media = $documento->getFirstMedia('archivos');
        if ($media) {
            return $media->toResponse(request());
        }
        abort(404, 'Archivo no encontrado.');
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
        $documento->update($request->all());

        return redirect()->route('proveedores.proveedors.show', $documento->documentable)->with('info', 'Se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documento $documento)
    {
        //
        $proveedor = $documento->documentable;
        
        // Si el documento pertenece a un representante legal, verificar si es el último
        if ($documento->documentable_type === 'App\Models\Proveedores\Apoderado') {
            $apoderado = $documento->documentable;
            if ($apoderado->tipo === 'representante') {
                // Contar documentos validados del representante
                $documentosValidados = $apoderado->documentos()
                    ->whereHas('validacion', function($query) {
                        $query->where('validado', true);
                    })
                    ->count();
                
                // Si este es el último documento validado, eliminar el representante
                if ($documentosValidados <= 1) {
                    $apoderado->delete();
                    return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se eliminó el documento y el representante legal');
                }
            }
        }
        
        $documento->delete();
        return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se eliminó con éxito');
    }
}
