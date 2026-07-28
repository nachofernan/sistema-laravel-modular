<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use App\Models\Documentos\Categoria;
use App\Models\Documentos\Descarga;
use App\Models\Documentos\Documento;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Documentos/Documentos/Ver', only: ['index', 'show']),
            // La baja es lógica (SoftDeletes), así que va con el permiso de edición en
            // vez de crear un permiso nuevo para el módulo.
            new Middleware('permission:Documentos/Documentos/Editar', only: ['edit', 'update', 'destroy']),
            new Middleware('permission:Documentos/Documentos/Crear', only: ['create', 'store']),
        ];
    }

    public function index()
    {
        return view('documentos.documentos.index');
    }

    public function create()
    {
        $categorias = Categoria::subcategorias()->with('padre')->get();

        return view('documentos.documentos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request, archivoRequerido: true);

        $documento = new Documento([
            'nombre' => $datos['nombre'],
            'codigo' => $datos['codigo'] ?? null,
            'descripcion' => $datos['descripcion'] ?? null,
            'observaciones' => $datos['observaciones'] ?? null,
            'version' => $datos['version'] ?? null,
            'categoria_id' => $datos['categoria_id'],
            'orden' => $datos['orden'] ?? 1000,
            'visible' => $request->boolean('visible'),
            'publico' => $request->boolean('publico'),
            'user_id' => Auth::id(),
        ]);

        $media = $documento->addMediaFromRequest('archivo')
            ->usingFileName($request->file('archivo')->getClientOriginalName())
            ->toMediaCollection('archivos');

        $documento->archivo = $media->file_name;
        $documento->mimeType = $media->mime_type;
        $documento->extension = $media->getExtensionAttribute();
        $documento->archivo_uploaded_at = now();
        $documento->save();

        return redirect()->route('documentos.documentos.show', $documento);
    }

    public function show(Documento $documento)
    {
        $documento->load('categoria.padre', 'user')->loadCount('descargas');

        return view('documentos.documentos.show', compact('documento'));
    }

    /**
     * Descarga desde el panel interno: no exige que el documento sea público, sí que
     * el usuario tenga permiso de ver el módulo (lo resuelve el middleware).
     */
    public function download(Documento $documento)
    {
        $media = $documento->getFirstMedia('archivos');

        abort_if($media === null, 404, 'Archivo no encontrado');

        Descarga::create([
            'documento_id' => $documento->id,
            'user_id' => Auth::id(),
            'ip' => request()->ip(),
        ]);

        return $media->toResponse(request());
    }

    public function edit(Documento $documento)
    {
        $categorias = Categoria::subcategorias()->with('padre')->get();

        return view('documentos.documentos.edit', compact('documento', 'categorias'));
    }

    public function update(Request $request, Documento $documento)
    {
        $datos = $this->validar($request, archivoRequerido: false);

        if ($request->hasFile('archivo')) {
            $documento->clearMediaCollection('archivos');
            $media = $documento->addMediaFromRequest('archivo')
                ->usingFileName($request->file('archivo')->getClientOriginalName())
                ->toMediaCollection('archivos');

            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->archivo_uploaded_at = now();
        }

        $documento->fill([
            'nombre' => $datos['nombre'],
            'codigo' => $datos['codigo'] ?? null,
            'descripcion' => $datos['descripcion'] ?? null,
            'observaciones' => $datos['observaciones'] ?? null,
            'version' => $datos['version'] ?? null,
            'categoria_id' => $datos['categoria_id'],
            'orden' => $datos['orden'] ?? $documento->orden,
            'visible' => $request->boolean('visible'),
            'publico' => $request->boolean('publico'),
        ])->save();

        return redirect()->route('documentos.documentos.show', $documento);
    }

    public function destroy(Documento $documento)
    {
        $documento->delete();

        return redirect()->route('documentos.documentos.index')
            ->with('success', 'Documento dado de baja.');
    }

    /**
     * El archivo es obligatorio al crear y opcional al editar: si no viene, se conserva
     * el que ya estaba cargado.
     */
    private function validar(Request $request, bool $archivoRequerido): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'codigo' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],
            'version' => ['nullable', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:documentos.categorias,id'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'archivo' => [
                $archivoRequerido ? 'required' : 'nullable',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,txt,jpg,jpeg,png,mp4',
            ],
        ], [
            'nombre.required' => 'El nombre del documento es obligatorio.',
            'categoria_id.required' => 'Hay que elegir una categoría.',
            'categoria_id.exists' => 'La categoría elegida no existe.',
            'archivo.required' => 'Hay que adjuntar el archivo del documento.',
            'archivo.max' => 'El archivo no puede superar los 50 MB.',
            'archivo.mimes' => 'El tipo de archivo no está permitido.',
        ]);
    }
}
