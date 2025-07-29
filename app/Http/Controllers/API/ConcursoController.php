<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Concursos\Concurso;
use App\Models\Concursos\Invitacion;
use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Documento;
use App\Models\Proveedores\DocumentoTipo;
use Illuminate\Support\Facades\DB;

class ConcursoController extends Controller
{
    public function __construct()
    {
        $this->middleware('verify.jwt');
    }

    /**
     * Listar concursos activos y terminados donde el proveedor es invitado.
     */
    public function index(Request $request)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        $concursos = Concurso::whereHas('invitaciones', function($q) use ($proveedor_id) {
                $q->where('proveedor_id', $proveedor_id);
            })
            ->with(['invitaciones' => function($q) use ($proveedor_id) {
                $q->where('proveedor_id', $proveedor_id);
            }])
            ->get();
        return response()->json([
            'success' => true,
            'data' => $concursos,
            'message' => 'Concursos obtenidos.'
        ]);
    }

    /**
     * Obtener info completa de un concurso (documentos, estado, contactos, sedes, prórrogas, etc.).
     */
    public function show(Request $request, $concurso_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        $concurso = Concurso::with([
                'documentos',
                'estado',
                'contactos',
                'sedes',
                'prorrogas',
                'invitaciones' => function($q) use ($proveedor_id) {
                    $q->where('proveedor_id', $proveedor_id);
                }
            ])
            ->findOrFail($concurso_id);
        return response()->json([
            'success' => true,
            'data' => $concurso,
            'message' => 'Concurso encontrado.'
        ]);
    }

    /**
     * Cambiar la intención en la invitación al concurso.
     */
    public function cambiarIntencion(Request $request, $concurso_id)
    {
        $request->validate([
            'intencion' => 'required|integer|in:0,1,2,3',
        ]);
        $proveedor_id = $request->attributes->get('proveedor_id');
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->firstOrFail();
        $invitacion->intencion = $request->intencion;
        $invitacion->save();
        return response()->json([
            'success' => true,
            'message' => 'Intención actualizada correctamente.'
        ]);
    }

    /**
     * Subir documento asociado a la invitación y tipo de documento.
     */
    public function subirDocumento(Request $request, $concurso_id)
    {
        $request->validate([
            'documento_tipo_id' => 'required|exists:documento_tipos,id',
            'file' => 'required|file',
        ]);
        $proveedor_id = $request->attributes->get('proveedor_id');
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->firstOrFail();
        DB::beginTransaction();
        try {
            $documento = new Documento([
                'documento_tipo_id' => $request->documento_tipo_id,
                'user_id_created' => null,
            ]);
            $invitacion->documentos()->save($documento);
            if ($request->hasFile('file')) {
                $media = $documento->addMediaFromRequest('file')
                    ->usingFileName($request->file('file')->getClientOriginalName())
                    ->toMediaCollection('archivos');
                $documento->archivo = $media->file_name;
                $documento->mimeType = $media->mime_type;
                $documento->extension = $media->getExtensionAttribute();
                $documento->file_storage = $media->getPath();
                $documento->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $documento,
                'message' => 'Documento subido correctamente. Pendiente de validación.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar si un documento requerido en el concurso ya está subido y validado como documento de proveedor.
     */
    public function verificarDocumentoProveedor(Request $request, $concurso_id, $documento_tipo_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        $proveedor = Proveedor::findOrFail($proveedor_id);
        $documento = $proveedor->traer_documento_valido($documento_tipo_id);
        if ($documento) {
            return response()->json([
                'success' => true,
                'data' => $documento,
                'message' => 'Documento válido encontrado.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No hay documento válido para este tipo.'
            ], 404);
        }
    }
} 