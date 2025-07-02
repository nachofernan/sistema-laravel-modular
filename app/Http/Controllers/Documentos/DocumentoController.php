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
        //
        $file = $request->file('archivo');
        $file_storage = $file->hashName();
        if(Storage::disk('documentos')->put($file_storage, file_get_contents($file))) {
            $documento = new Documento([
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'version' => $request->input('version'),
                'archivo' => $file->getClientOriginalName(),
                'mimeType' => $file->getClientMimeType(),
                'extension' => $file->extension(),
                'file_storage' => $file_storage,
                'archivo_uploaded_at' => \Carbon\Carbon::now(),
                'categoria_id' => $request->input('categoria_id'),
                'sede_id' => is_numeric($request->input('sede_id')) ? $request->input('sede_id') : null,
                'user_id' => Auth::user()->id,
            ]);
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
        //
        Descarga::create([
            'documento_id' => $documento->id,
            'user_id' => Auth::user()->id ?? 1,
        ]);
        return response()->file(storage_path('app/public/documentos/').$documento->file_storage);
        //return Storage::disk('public')->get($documento->file_storage, $documento->archivo);
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
        //
        $file = $request->file('archivo');
        
        if($file) {
            $file_storage = $file->hashName();
            if(Storage::disk('documentos')->put($file_storage, file_get_contents($file))) {
                $documento->archivo = $file->getClientOriginalName();
                $documento->mimeType = $file->getClientMimeType();
                $documento->extension = $file->extension();
                $documento->file_storage = $file_storage;
                $documento->archivo_uploaded_at = \Carbon\Carbon::now();
            }
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
