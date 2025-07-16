<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use App\Models\Documentos\Categoria;
use App\Models\Documentos\Descarga;
use App\Models\Documentos\Documento;
use App\Models\Usuarios\Sede;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Documentos/Documentos/Ver', only: ['index', 'show']),
            new Middleware('permission:Documentos/Documentos/Editar', only: ['edit', 'update']),
            new Middleware('permission:Documentos/Documentos/Crear', only: ['create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('documentos.documentos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sedes = Sede::all();
        $categorias = Categoria::whereNotNull('categoria_padre_id')->orderBy('categoria_padre_id', 'asc')->get();
        return view('documentos.documentos.create', compact('sedes', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $documento = new Documento([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'version' => $request->input('version'),
            'categoria_id' => $request->input('categoria_id'),
            'sede_id' => is_numeric($request->input('sede_id')) ? $request->input('sede_id') : null,
            'user_id' => Auth::user()->id,
        ]);
        $documento->save();
        if ($request->hasFile('archivo')) {
            $media = $documento->addMediaFromRequest('archivo')
                ->usingFileName($request->file('archivo')->getClientOriginalName())
                ->toMediaCollection('archivos');
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $media->getPath();
            $documento->archivo_uploaded_at = now();
            $documento->save();
        }
        return redirect()->route('documentos.documentos.show', $documento);
    }

    /**
     * Display the specified resource.
     */
    public function show(Documento $documento)
    {
        //
        return view('documentos.documentos.show', compact('documento'));
    }

    /**
     * Display the specified resource.
     */
    public function download(Documento $documento)
    {
        Descarga::create([
            'documento_id' => $documento->id,
            'user_id' => Auth::user()->id ?? 1,
        ]);
        $media = $documento->getFirstMedia('archivos');
        if ($media) {
            return $media->toResponse(request());
        }
        abort(404, 'Archivo no encontrado');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Documento $documento)
    {
        //
        $sedes = Sede::all();
        $categorias = Categoria::whereNotNull('categoria_padre_id')->orderBy('categoria_padre_id', 'asc')->get();
        return view('documentos.documentos.edit', compact('documento', 'sedes', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documento $documento)
    {
        $file = $request->file('archivo');
        if ($file) {
            // Eliminar archivo anterior si existe
            $documento->clearMediaCollection('archivos');
            $media = $documento->addMedia($file)
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection('archivos');
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $media->getPath();
            $documento->archivo_uploaded_at = now();
        }
        $documento->nombre = $request->input('nombre');
        $documento->descripcion = $request->input('descripcion');
        $documento->categoria_id = $request->input('categoria_id');
        $documento->sede_id = is_numeric($request->input('sede_id')) ? $request->input('sede_id') : null;
        $documento->user_id = Auth::user()->id;
        $documento->orden = $request->input('orden');
        $documento->visible = $request->input('visible');
        $documento->save();
        return redirect()->route('documentos.documentos.show', $documento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documento $documento)
    {
        //
    }
}
