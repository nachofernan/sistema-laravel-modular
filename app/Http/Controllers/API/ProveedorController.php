<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Rubro;
use App\Models\Proveedores\Subrubro;
use App\Models\Proveedores\DocumentoTipo;
use App\Models\Proveedores\Apoderado;
use App\Models\Proveedores\Documento;
use App\Http\Resources\API\ProveedorResource;
use App\Http\Resources\API\RubroResource;
use App\Http\Resources\API\SubrubroResource;
use App\Http\Resources\API\DocumentoResource;
use App\Http\Resources\API\ApoderadoResource;
use App\Http\Requests\API\ProveedorSyncSubrubrosRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('verify.jwt');
    }

    /**
     * Obtener todos los datos de un proveedor por CUIT.
     */
    public function show($cuit)
    {
        $proveedor = Proveedor::where('cuit', $cuit)
            ->with([
                'contactos',
                'direcciones',
                'subrubros.rubro',
                'documentos', // Ya filtra por validados por defecto
                'apoderados' // Ya filtra por validados por defecto
            ])
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'data' => new ProveedorResource($proveedor),
            'message' => 'Proveedor encontrado.'
        ]);
    }

    /**
     * Obtener todos los tipos de documentos y apoderados.
     */
    public function tiposDocumentos()
    {
        $tipos = DocumentoTipo::all();
        $apoderados = Apoderado::select('id', 'nombre')->distinct()->get();
        return response()->json([
            'success' => true,
            'data' => [
                'tipos_documentos' => $tipos,
                'tipos_apoderados' => $apoderados
            ],
            'message' => 'Tipos obtenidos.'
        ]);
    }

    /**
     * Obtener todos los tipos de rubros y subrubros.
     */
    public function tiposRubros()
    {
        $rubros = Rubro::with('subrubros')->get();
        return response()->json([
            'success' => true,
            'data' => RubroResource::collection($rubros),
            'message' => 'Rubros y subrubros obtenidos.'
        ]);
    }

    /**
     * Asociar subrubros a un proveedor (sync).
     */
    public function syncSubrubros(ProveedorSyncSubrubrosRequest $request, $cuit)
    {
        $proveedor = Proveedor::where('cuit', $cuit)->firstOrFail();
        $proveedor->subrubros()->sync($request->subrubro_ids);
        return response()->json([
            'success' => true,
            'message' => 'Subrubros actualizados correctamente.'
        ]);
    }

    /**
     * Subir un documento para el proveedor (queda pendiente de validación).
     */
    public function subirDocumento(Request $request, $cuit)
    {
        // Validar los datos de entrada
        $request->validate([
            'documento_tipo_id' => 'required|exists:proveedores.documento_tipos,id',
            'file' => 'required|file',
            'vencimiento' => 'nullable|date',
        ]);

        // Obtener el proveedor
        $proveedor = Proveedor::where('cuit', $cuit)->firstOrFail();

        // Iniciar transacción
        DB::beginTransaction();
        
        try {
            // Procesar el archivo
            $archivo = $request->file('file');
            $nombreArchivo = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            $mimeType = $archivo->getMimeType();
            
            // Crear el documento en la base de datos
            $documento = $proveedor->documentos(false)->create([
                'documento_tipo_id' => $request->documento_tipo_id,
                'user_id_created' => 1,
                'vencimiento' => $request->vencimiento ?? null,
                'archivo' => $nombreArchivo,
                'extension' => $extension,
                'mimeType' => $mimeType,
                'file_storage' => 'temp_' . time(), // Valor temporal
            ]);

            // Crear la validación
            $documento->validacion()->create([
                'user_id' => null,
                'validado' => false,
            ]);

            // Confirmar transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => new DocumentoResource($documento),
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
     * Descargar un documento validando acceso.
     */
    public function descargarDocumento($cuit, $documento_id)
    {
        $proveedor = Proveedor::where('cuit', $cuit)->firstOrFail();
        $documento = $proveedor->documentos()->where('id', $documento_id)->firstOrFail();
        // Validar que el documento pertenece al proveedor y está validado
        if (!$documento->esValido()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado o documento no válido.'
            ], 403);
        }
        $media = $documento->getFirstMedia('archivos');
        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Archivo no encontrado.'
            ], 404);
        }
        return response()->download($media->getPath(), $media->file_name);
    }

    /**
     * Obtener solo los últimos documentos validados por tipo de documento.
     */
    public function ultimosDocumentosValidados($cuit)
    {
        $proveedor = Proveedor::where('cuit', $cuit)->firstOrFail();
        
        $documentos = $proveedor->ultimosDocumentosValidados()
            ->load('documentoTipo');
        
        return response()->json([
            'success' => true,
            'data' => DocumentoResource::collection($documentos),
            'message' => 'Documentos obtenidos correctamente.'
        ]);
    }
} 