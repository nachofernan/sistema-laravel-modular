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
        $file = $request->file('file');
        $file_storage = $file->hashName();
        if(Storage::disk('capacitaciones')->put($file_storage, file_get_contents($file))) {
            $documento = new Documento([
                'nombre' => $request->input('nombre'),
                'capacitacion_id' => $request->input('capacitacion_id'),
                'archivo' => $file->getClientOriginalName(),
                'mimeType' => $file->getClientMimeType(),
                'extension' => $file->extension(),
                'file_storage' => $file_storage,
            ]);
            $documento->save();
        }
        return redirect()->route('capacitaciones.capacitacions.show', $documento->capacitacion);
    }

    /**
     * Display the specified resource.
     */
    public function show(Documento $documento)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function download(Documento $documento)
    {
        //
        $user = User::find(Auth::user()->id);
        if(
            $user->hasRole('Capacitaciones/Acceso') ||
            Invitacion::where('capacitacion_id', $documento->capacitacion->id)->where('user_id', $user->id)->count()
        ) {
            return response()->file(storage_path('app/public/capacitaciones/').$documento->file_storage);
        }
        else
        {
            return "No tiene los permisos";
        }
        //return Storage::disk('public')->get($documento->file_storage, $documento->archivo);
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
        Storage::disk('capacitaciones')->delete($documento->file_storage);
        $capacitacion = $documento->capacitacion;
        $documento->delete();
        return redirect()->route('capacitaciones.capacitacions.show', $capacitacion);
    }
}
