<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Rubro;
use App\Models\Proveedores\DocumentoTipo;
use App\Models\Proveedores\Apoderado;
use App\Http\Resources\API\ProveedorResource;
use App\Http\Resources\API\RubroResource;
use App\Http\Requests\API\ProveedorSyncSubrubrosRequest;
use App\Http\Resources\API\DocumentoResource;
use App\Http\Resources\API\ApoderadoResource;
use App\Models\Proveedores\Documento;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'documento_tipo_id' => 'required',
            'file' => 'required|file',
            'vencimiento' => 'nullable|date',
        ]);

        // Obtener el proveedor
        $proveedor = Proveedor::where('cuit', $cuit)->firstOrFail();

        // Iniciar transacción
        DB::beginTransaction();
        
        try {
            // Crear el documento en la base de datos con valores temporales
            $documento = $proveedor->documentos(false)->create([
                'documento_tipo_id' => $request->documento_tipo_id,
                'user_id_created' => 1,
                'vencimiento' => $request->vencimiento ?? null,
                'archivo' => 'x',
                'extension' => 'x',
                'mimeType' => 'x',
                'file_storage' => 'x',
            ]);

            // Procesar el archivo con Spatie Media Library
            if ($request->hasFile('file')) {
                $media = $documento->addMediaFromRequest('file')
                    ->usingFileName($request->file('file')->getClientOriginalName())
                    ->toMediaCollection('archivos', 'proveedores');
                
                // Actualizar metadatos en el modelo Documento
                $documento->archivo = $media->file_name;
                $documento->mimeType = $media->mime_type;
                $documento->extension = $media->getExtensionAttribute();
                $documento->file_storage = $request->file('file')->getClientOriginalName();
                $documento->save();
            }

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
            Log::error('Error al subir documento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar un documento validando acceso.
     * Endpoint API para descargar un documento de proveedor.
     * GET /api/proveedores/{cuit}/documentos/{documento_id}/descargar
     */
    public function descargarDocumento(Request $request, $cuit, $documento_id)
    {
        // Buscar el proveedor por CUIT
        $proveedor = Proveedor::where('cuit', $cuit)->first();

        if (!$proveedor) {
            return response()->json([
                'success' => false,
                'message' => 'Proveedor no encontrado.'
            ], 404);
        }

        // Buscar el documento por ID - primero en documentos del proveedor
        $documento = $proveedor->documentos()->where('id', $documento_id)->first();

        // Si no se encuentra en documentos del proveedor, buscar en documentos de apoderados
        if (!$documento) {
            $apoderados = $proveedor->apoderados;
            foreach ($apoderados as $apoderado) {
                $documento = $apoderado->documentos()->where('id', $documento_id)->first();
                if ($documento) {
                    break; // Encontramos el documento en este apoderado
                }
            }
        }

        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Documento no encontrado.'
            ], 404);
        }

        // Validar si el documento es válido para descarga
        if (!$documento->estaValidado()) {
            return response()->json([
                'success' => false,
                'message' => 'Documento no válido.'
            ], 403);
        }

        // Obtener el archivo asociado al documento
        $media = $documento->getFirstMedia('archivos');

        if ($media) {
            // Descargar el archivo como respuesta API
            return response()->download($media->getPath(), $media->file_name);
        }

        // Si no existe el archivo físico
        return response()->json([
            'success' => false,
            'message' => 'Archivo no encontrado.'
        ], 404);
    }

    /**
     * Obtener solo los últimos documentos validados por tipo de documento.
     */
    public function ultimosDocumentosValidados($cuit)
    {
        $proveedor = Proveedor::where('cuit', $cuit)->firstOrFail();
        
        $documentos = $proveedor->ultimosDocumentosValidados();
        
        return response()->json([
            'success' => true,
            'data' => DocumentoResource::collection($documentos),
            'message' => 'Documentos obtenidos correctamente.'
        ]);
    }

    /**
     * Obtener los apoderados de un proveedor.
     */
    public function apoderados($cuit)
    {
        $proveedor = Proveedor::where('cuit', $cuit)->firstOrFail();
        
        $apoderados = $proveedor->apoderados(false)->with('documentos.validacion')->get();
        
        // Filtrar apoderados que tengan documentos validados para el Resource
        $apoderadosConDocumentos = $apoderados->filter(function ($apoderado) {
            return $apoderado->documentos(true)->exists();
        });
        
        return response()->json([
            'success' => true,
            'data' => ApoderadoResource::collection($apoderadosConDocumentos),
            'message' => 'Apoderados obtenidos correctamente.'
        ]);
    }

    /**
     * Subir un apoderado para el proveedor (queda pendiente de validación).
     */
    public function subirApoderado(Request $request, $cuit)
    {
        // Validar los datos de entrada
        $request->validate([
            'tipo' => 'required|in:representante,apoderado',
            'nombre' => 'nullable|string|max:255',
            'file' => 'required|file',
            'vencimiento' => 'nullable|date',
        ]);

        // Obtener el proveedor
        $proveedor = Proveedor::where('cuit', $cuit)->firstOrFail();

        // Iniciar transacción
        DB::beginTransaction();
        
        try {
            // Crear el apoderado
            $apoderado = $proveedor->apoderados()->create([
                'nombre' => $request->tipo === 'representante' ? ($request->nombre ?? null) : null,
                'tipo' => $request->tipo,
                'activo' => true,
            ]);

            // Crear el documento en la base de datos con valores temporales
            $documento = $apoderado->documentos(false)->create([
                'user_id_created' => 1,
                'vencimiento' => $request->vencimiento ?? null,
                'archivo' => 'x',
                'extension' => 'x',
                'mimeType' => 'x',
                'file_storage' => 'x',
            ]);

            // Procesar el archivo con Spatie Media Library
            if ($request->hasFile('file')) {
                $media = $documento->addMediaFromRequest('file')
                    ->usingFileName($request->file('file')->getClientOriginalName())
                    ->toMediaCollection('archivos', 'proveedores');
                
                // Actualizar metadatos en el modelo Documento
                $documento->archivo = $media->file_name;
                $documento->mimeType = $media->mime_type;
                $documento->extension = $media->getExtensionAttribute();
                $documento->file_storage = $request->file('file')->getClientOriginalName();
                $documento->save();
            }

            // Crear la validación
            $documento->validacion()->create([
                'user_id' => null,
                'validado' => false,
            ]);

            // Confirmar transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'apoderado' => [
                        'id' => $apoderado->id,
                        'nombre' => $apoderado->nombre,
                        'tipo' => $apoderado->tipo,
                        'activo' => $apoderado->activo,
                        'proveedor_id' => $apoderado->proveedor_id,
                        'created_at' => $apoderado->created_at,
                        'updated_at' => $apoderado->updated_at,
                    ],
                    'documento' => new DocumentoResource($documento)
                ],
                'message' => 'Apoderado subido correctamente. Pendiente de validación.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al subir apoderado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el apoderado: ' . $e->getMessage()
            ], 500);
        }
    }
} 