<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use App\Models\Documentos\Categoria;
use App\Models\Documentos\Descarga;
use App\Models\Documentos\Documento;
use App\Models\Documentos\DocumentoVersion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Documentos/Documentos/Ver', only: ['index', 'show', 'downloadVersion']),
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
            'categoria_id' => $datos['categoria_id'],
            'orden' => $datos['orden'] ?? 1000,
            'visible' => $request->boolean('visible'),
            'publico' => $request->boolean('publico'),
            // Un documento puede llegar ya versionado desde Control de Gestión.
            'version' => $datos['version'],
            'user_id' => Auth::id(),
        ]);

        // MediaLibrary persiste el documento al adjuntarle el archivo; el save de
        // abajo guarda los metadatos que deja `reemplazarArchivo`.
        $documento->reemplazarArchivo($request->file('archivo'), usuarioId: Auth::id());
        $documento->save();

        return redirect()->route('documentos.documentos.show', $documento);
    }

    public function show(Documento $documento)
    {
        $documento->load('categoria.padre', 'user', 'versiones.usuario')->loadCount('descargas');

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

    /**
     * Descarga un archivo del historial. No se registra en `descargas`: esa tabla
     * mide el consumo del documento vigente, no las consultas al archivo muerto.
     */
    public function downloadVersion(Documento $documento, DocumentoVersion $version)
    {
        abort_unless($version->documento_id === $documento->id, 404);

        $media = $version->media();

        abort_if($media === null, 404, 'El archivo de esta versión ya no está disponible');

        return $media->toResponse(request());
    }

    public function edit(Documento $documento)
    {
        $categorias = Categoria::subcategorias()->with('padre')->get();

        return view('documentos.documentos.edit', compact('documento', 'categorias'));
    }

    public function update(Request $request, Documento $documento)
    {
        // Subiendo un archivo, la versión actual pasa al historial: la nueva tiene que
        // ser mayor. Sin archivo, el número se puede corregir sin bajar de lo archivado.
        $datos = $this->validar($request, archivoRequerido: false, versionMinima: $request->hasFile('archivo')
            ? $documento->version + 1
            : (int) $documento->versiones()->max('version') + 1);

        // El archivo anterior no se borra: pasa al historial y el documento sube de versión.
        if ($request->hasFile('archivo')) {
            $documento->reemplazarArchivo(
                $request->file('archivo'),
                $datos['notas_version'] ?? null,
                Auth::id(),
                $datos['version'],
            );
        }

        $documento->fill([
            'nombre' => $datos['nombre'],
            'codigo' => $datos['codigo'] ?? null,
            'descripcion' => $datos['descripcion'] ?? null,
            'observaciones' => $datos['observaciones'] ?? null,
            'version' => $datos['version'],
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
     *
     * `$versionMinima` evita que la numeración retroceda y pise una versión ya
     * archivada, que rompería el índice único de `documento_versiones`.
     */
    private function validar(Request $request, bool $archivoRequerido, int $versionMinima = 1): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'codigo' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],
            'version' => ['required', 'integer', "min:{$versionMinima}"],
            'notas_version' => ['nullable', 'string', 'max:255'],
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
            'version.required' => 'Hay que indicar el número de versión.',
            'version.min' => "La versión no puede ser menor que {$versionMinima}: ese número ya lo usa una versión anterior.",
            'archivo.required' => 'Hay que adjuntar el archivo del documento.',
            'archivo.max' => 'El archivo no puede superar los 50 MB.',
            'archivo.mimes' => 'El tipo de archivo no está permitido.',
        ]);
    }
}
