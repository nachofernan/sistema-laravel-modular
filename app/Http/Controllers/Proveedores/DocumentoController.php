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
        $proveedor = Proveedor::find($request->all('proveedor_id')['proveedor_id']);

        $file = $request->file('file');
        $file_storage = $file->hashName();
        if(Storage::disk('proveedores')->put($file_storage, file_get_contents($file))) {
            $documento = $proveedor->documentos()->create([
                'archivo' => $file->getClientOriginalName(),
                'mimeType' => $file->getClientMimeType(),
                'extension' => $file->extension(),
                'file_storage' => $file_storage,
                'user_id_created' => Auth::user()->id,
                'vencimiento' => $request->all('vencimiento')['vencimiento'] ?? null,
                'documento_tipo_id' => $request->all('documento_tipo_id')['documento_tipo_id'],
            ]);
            $documento->validacion()->create();
            //Mail::to(['egaitero@buenosairesenergia.com.ar', 'jprojeda@buenosairesenergia.com.ar', 'mmartin@buenosairesenergia.com.ar'])->send(new NuevoArchivoValidacion($documento->validacion));
            EmailHelper::enviarNotificacion(
                ['egaitero@buenosairesenergia.com.ar', /* 'jprojeda@buenosairesenergia.com.ar', 'mmartin@buenosairesenergia.com.ar' */],
                new NuevoArchivoValidacion($documento->validacion),
                'Nuevo archivo para validación del proveedor ' . $proveedor->razonsocial
            );
        }

        return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Documento $documento)
    {
        //
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
        $documento->delete();
        return redirect()->route('proveedores.proveedors.show', $proveedor)->with('info', 'Se eliminó con éxito');
    }
}
