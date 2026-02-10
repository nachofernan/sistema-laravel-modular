<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Concursos\Concurso;
use App\Models\Concursos\Invitacion;
use App\Models\Concursos\ConcursoDocumento;
use App\Models\Concursos\OfertaDocumento;
use App\Models\Concursos\DocumentoTipo as DocumentoTipoConcurso;
use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Documento;
use App\Models\Proveedores\DocumentoTipo;
use App\Http\Resources\API\ConcursoResource;
use App\Http\Resources\API\InvitacionResource;
use App\Http\Resources\API\ConcursoDocumentoResource;
use App\Http\Resources\API\OfertaDocumentoResource;
use App\Http\Resources\API\DocumentoTipoResource;
use App\Http\Resources\API\DocumentoTipoOfertaResource;
use App\Http\Resources\API\DocumentoAdicionalResource;
use App\Http\Requests\API\ConcursoCambiarIntencionRequest;
use App\Http\Requests\API\ConcursoSubirDocumentoRequest;
use App\Models\Media;
use App\Services\ConcursoEncryptionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            ->with([
                'invitaciones' => function($q) use ($proveedor_id) {
                    $q->where('proveedor_id', $proveedor_id);
                },
                'estado',
                'subrubro.rubro',
                'documentos_requeridos'
            ])
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => ConcursoResource::collection($concursos),
            'message' => 'Concursos obtenidos correctamente.'
        ]);
    }

    /**
     * Obtener info completa de un concurso (documentos, estado, contactos, sedes, prórrogas, etc.).
     */
    public function show(Request $request, $concurso_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        $concurso = Concurso::with([
                'documentos.documentoTipo',
                'estado',
                'contactos',
                'sedes',
                'prorrogas',
                'documentos_requeridos',
                'subrubro.rubro',
                'invitaciones' => function($q) use ($proveedor_id) {
                    $q->where('proveedor_id', $proveedor_id);
                }
            ])
            ->findOrFail($concurso_id);
        
        return response()->json([
            'success' => true,
            'data' => new ConcursoResource($concurso),
            'message' => 'Concurso encontrado correctamente.'
        ]);
    }

    /**
     * Cambiar la intención en la invitación al concurso.
     */
    public function cambiarIntencion(ConcursoCambiarIntencionRequest $request, $concurso_id)
    {
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
     * Si documento_tipo_id es null, se considera un documento adicional.
     */
    public function subirDocumento(ConcursoSubirDocumentoRequest $request, $concurso_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->firstOrFail();
        
        // Obtener el concurso para validaciones
        $concurso = $invitacion->concurso;
        
        // Validar estado del concurso y fecha de cierre
        if ($concurso->estado_id == 2) { // Estado Activo
            // Si está en estado activo pero ya pasó la fecha de cierre, no permitir subida
            if (now()->gte($concurso->fecha_cierre)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden subir documentos. El concurso ya ha cerrado.'
                ], 403);
            }
        } elseif ($concurso->estado_id == 3) { // Estado Análisis
            // En estado análisis, solo permitir documentos adicionales si está activada la función permite_carga
            if (!$concurso->permite_carga) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden subir documentos en esta etapa del concurso.'
                ], 403);
            }
            
            // Si es un documento requerido (tiene documento_tipo_id), no permitir en estado análisis
            if ($request->documento_tipo_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden subir documentos requeridos en esta etapa del concurso. Solo se permiten documentos adicionales.'
                ], 403);
            }
        } else {
            // Para otros estados (1: Borrador, 4: Finalizado, etc.), no permitir subida
            return response()->json([
                'success' => false,
                'message' => 'No se pueden subir documentos en el estado actual del concurso.'
            ], 403);
        }
        
        DB::beginTransaction();
        try {
            Log::info('Iniciando subida de documento', [
                'concurso_id' => $concurso_id,
                'proveedor_id' => $proveedor_id,
                'estado_concurso' => $concurso->estado_id,
                'tiene_archivo' => $request->hasFile('file'),
                'documento_tipo_id' => $request->documento_tipo_id
            ]);
            
            // Crear el documento en la base de datos con valores temporales
            $encriptado = $concurso->estado_id == 2;
            Log::info('Documento será encriptado', ['encriptado' => $encriptado]);
            
            $documento = new OfertaDocumento([
                'invitacion_id' => $invitacion->id,
                'documento_tipo_id' => $request->documento_tipo_id, // Puede ser null para documentos adicionales
                'documento_proveedor_id' => null,
                'user_id_created' => null, // Siempre null para API (proveedor)
                'archivo' => 'x',
                'extension' => 'x',
                'mimeType' => 'x',
                'file_storage' => 'x',
                'encriptado' => $encriptado,
                'comentarios' => $request->comentarios,
            ]);
            
            // Guardar el documento ANTES de procesar el archivo para que Spatie Media Library tenga el ID
            $documento->save();
            
            Log::info('Documento guardado en BD', [
                'documento_id' => $documento->id,
                'invitacion_id' => $documento->invitacion_id
            ]);
            
            // Procesar el archivo con Spatie Media Library
            if ($request->hasFile('file')) {
                $originalFileName = $request->file('file')->getClientOriginalName();
                $originalExtension = $request->file('file')->getClientOriginalExtension();
                
                Log::info('Procesando archivo', [
                    'archivo_original' => $originalFileName,
                    'extension' => $originalExtension,
                    'tamaño' => $request->file('file')->getSize(),
                    'mime_type' => $request->file('file')->getMimeType()
                ]);
                
                // Si el concurso está en estado activo, encriptar el archivo
                if ($encriptado) {
                    Log::info('Iniciando encriptación de archivo');
                    
                    // Crear nombre de archivo encriptado
                    $encryptedFileName = $originalFileName . '.enc';
                    
                    // Guardar archivo temporalmente
                    $tempPath = $request->file('file')->getRealPath();
                    $encryptedPath = storage_path('app/temp/' . $encryptedFileName);
                    
                    Log::info('Rutas de archivos', [
                        'temp_path' => $tempPath,
                        'encrypted_path' => $encryptedPath,
                        'temp_exists' => file_exists($tempPath),
                        'temp_size' => file_exists($tempPath) ? filesize($tempPath) : 'N/A'
                    ]);
                    
                    // Asegurar que el directorio temporal existe
                    if (!file_exists(dirname($encryptedPath))) {
                        mkdir(dirname($encryptedPath), 0755, true);
                        Log::info('Directorio temporal creado', ['path' => dirname($encryptedPath)]);
                    }
                    
                    // Encriptar el archivo
                    Log::info('Iniciando encriptación con servicio');
                    $encryptionService = new ConcursoEncryptionService();
                    $encryptionResult = $encryptionService->encryptFile($tempPath, $encryptedPath);
                    
                    Log::info('Encriptación completada', [
                        'resultado' => $encryptionResult,
                        'archivo_encriptado_existe' => file_exists($encryptedPath),
                        'archivo_encriptado_tamaño' => file_exists($encryptedPath) ? filesize($encryptedPath) : 'N/A'
                    ]);
                    
                    // Agregar archivo encriptado a media library
                    Log::info('Agregando archivo encriptado a media library');
                    $media = $documento->addMedia($encryptedPath)
                        ->usingFileName($encryptedFileName)
                        ->toMediaCollection('archivos', 'concursos');
                    
                    Log::info('Media creado', [
                        'media_id' => $media->id,
                        'media_file_name' => $media->file_name,
                        'media_path' => $media->getPath(),
                        'media_exists' => file_exists($media->getPath())
                    ]);
                    
                    // Limpiar archivo temporal (verificar que existe antes de eliminar)
                    if (file_exists($encryptedPath)) {
                        unlink($encryptedPath);
                        Log::info('Archivo temporal eliminado');
                    } else {
                        Log::info('Archivo temporal ya no existe (probablemente movido por Spatie)');
                    }
                    
                    Log::info('Documento encriptado subido exitosamente', [
                        'documento_id' => $documento->id,
                        'archivo_original' => $originalFileName,
                        'archivo_encriptado' => $encryptedFileName,
                        'concurso_id' => $concurso_id,
                        'media_id' => $media->id
                    ]);
                } else {
                    Log::info('Subiendo archivo sin encriptar');
                    
                    // Si no está encriptado, subir normalmente
                    $media = $documento->addMediaFromRequest('file')
                        ->usingFileName($originalFileName)
                        ->toMediaCollection('archivos', 'concursos');
                    
                    Log::info('Media creado sin encriptar', [
                        'media_id' => $media->id,
                        'media_file_name' => $media->file_name,
                        'media_path' => $media->getPath(),
                        'media_exists' => file_exists($media->getPath())
                    ]);
                }
                
                // Actualizar metadatos en el modelo Documento (para ambos casos)
                Log::info('Actualizando metadatos del documento', [
                    'documento_id' => $documento->id,
                    'media_file_name' => $media->file_name,
                    'media_mime_type' => $media->mime_type,
                    'media_extension' => $media->getExtensionAttribute(),
                    'file_storage' => $originalFileName
                ]);
                
                $documento->archivo = $media->file_name;
                $documento->mimeType = $media->mime_type;
                $documento->extension = $media->getExtensionAttribute();
                $documento->file_storage = $originalFileName; // Guardar nombre original
                $documento->save(); // Usar save() para persistir los cambios
                
                Log::info('Metadatos actualizados', [
                    'documento_id' => $documento->id,
                    'archivo' => $documento->archivo,
                    'mimeType' => $documento->mimeType,
                    'extension' => $documento->extension,
                    'file_storage' => $documento->file_storage,
                    'encriptado' => $documento->encriptado
                ]);
            }
            
            DB::commit();
            
            // Determinar qué resource usar basado en si es documento requerido o adicional
            if ($documento->documento_tipo_id) {
                $resource = new OfertaDocumentoResource($documento->load('documentoTipo'));
                $message = 'Documento subido correctamente.';
            } else {
                $resource = new DocumentoAdicionalResource($documento->load('creador'));
                $message = 'Documento adicional subido correctamente.';
            }
            
            return response()->json([
                'success' => true,
                'data' => $resource,
                'message' => $message
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
     * CRÍTICO: Solo considera documentos válidos ANTES del cierre del concurso para mantener consistencia auditable.
     */
    public function verificarDocumentoProveedor(Request $request, $concurso_id, $documento_tipo_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        
        // Obtener la invitación para acceder al concurso y su fecha de cierre
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->firstOrFail();
        
        $fecha_cierre = $invitacion->concurso->fecha_cierre;
        
        Log::info('Verificando documento proveedor con consistencia auditable - Fecha de cierre: ' . $fecha_cierre);
        
        $proveedor = Proveedor::findOrFail($proveedor_id);
        $documento = $proveedor->traer_documento_valido($documento_tipo_id, $fecha_cierre);
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

    /**
     * Obtener tipos de documentos disponibles para concursos.
     */
    public function tiposDocumentos(Request $request)
    {
        $tipos = DocumentoTipoConcurso::where('de_concurso', true)
            ->with('tipo_documento_proveedor')
            ->get();
        
        // Obtener el proveedor_id si está disponible
        $proveedor_id = $request->attributes->get('proveedor_id');
        
        // Crear los recursos pasando el proveedor_id como parámetro adicional
        $resources = $tipos->map(function ($tipo) use ($proveedor_id) {
            return new DocumentoTipoResource($tipo, $proveedor_id);
        });
        
        return response()->json([
            'success' => true,
            'data' => $resources,
            'message' => 'Tipos de documentos obtenidos correctamente.'
        ]);
    }

    /**
     * Obtener tipos de documentos de oferta con documentos ya cargados por el proveedor.
     */
    public function tiposDocumentosOferta(Request $request, $concurso_id)
    {
        $proveedor_id = (int) $request->attributes->get('proveedor_id');
        $concurso_id = (int) $concurso_id; // Convertir a integer
        
        Log::info('Debug tiposDocumentosOferta', [
            'proveedor_id' => $proveedor_id,
            'concurso_id' => $concurso_id,
            'proveedor_id_type' => gettype($proveedor_id),
            'concurso_id_type' => gettype($concurso_id)
        ]);
        
        // Verificar que el proveedor tiene acceso al concurso
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->firstOrFail();
        
        // Obtener el concurso para acceder a la fecha de cierre
        $concurso = $invitacion->concurso;
        $fecha_cierre = $concurso->fecha_cierre;
        
        Log::info('Aplicando consistencia auditable en API - Fecha de cierre: ' . $fecha_cierre);
        
        // Obtener tipos de documentos de oferta específicamente asociados al concurso
        $tipos = $concurso->documentos_requeridos()
            ->with('tipo_documento_proveedor')
            ->get();
        
        // Crear los recursos pasando el proveedor_id, concurso_id y fecha_cierre como parámetros
        $resources = $tipos->map(function ($tipo) use ($proveedor_id, $concurso_id, $fecha_cierre) {
            return new DocumentoTipoOfertaResource($tipo, $proveedor_id, $concurso_id, $fecha_cierre);
        });
        
        return response()->json([
            'success' => true,
            'data' => $resources,
            'message' => 'Tipos de documentos de oferta obtenidos correctamente.'
        ]);
    }



    /**
     * Descargar un documento del concurso.
     */
    public function descargarDocumento(Request $request, $concurso_id, $documento_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        Log::info('Info recibida', ['concurso_id' => $concurso_id, 'documento_id' => $documento_id, 'proveedor_id' => $proveedor_id]);
        
        // 1. Verificar acceso
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->firstOrFail();

        // 2. Buscar el media
        $media = Media::find($documento_id);
        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Archivo no encontrado.'], 404);
        }

        Log::info('Media encontrado', ['media' => $media]);

        // 3. Identificar el modelo CORRECTAMENTE usando model_type
        $documento = null;
        $esDocumentoConcurso = false;

        if ($media->model_type === 'App\Models\Concursos\OfertaDocumento') {
            $documento = OfertaDocumento::where('id', $media->model_id)
                ->whereHas('invitacion', function($q) use ($concurso_id) {
                    $q->where('concurso_id', $concurso_id);
                })->first();
        } 
        elseif ($media->model_type === 'App\Models\Concursos\ConcursoDocumento') {
            $documento = ConcursoDocumento::where('id', $media->model_id)
                ->where('concurso_id', $concurso_id)
                ->first();
            
            if ($documento) {
                $esDocumentoConcurso = true;
            }
        }

        Log::info('Documento encontrado', ['documento' => $documento, 'esDocumentoConcurso' => $esDocumentoConcurso]);

        if (!$documento) {
            return response()->json(['success' => false, 'message' => 'No se encontró el documento asociado.'], 404);
        }
        
        $filePath = $media->getPath();
        $downloadFileName = $documento->file_storage ?: $media->file_name;

        // --- LÓGICA DE DESCARGA SEGURA ---

        // CASO A: Es de concurso -> Descarga directa (Nunca encriptado)
        if ($esDocumentoConcurso) {
            Log::info('Descargando archivo de concurso (público)', ['id' => $documento->id]);
            return response()->download($filePath, $downloadFileName);
        }

        // CASO B: Es de oferta -> Verificar bandera en BD o servicio
        $encryptionService = new ConcursoEncryptionService();
        
        // Aquí priorizamos la base de datos si el campo existe, 
        // sino dejamos que el service verifique el archivo
        $debeDesencriptar = isset($documento->encriptado) ? $documento->encriptado : $encryptionService->isEncrypted($filePath);

        if ($debeDesencriptar) {
            Log::info('Descargando archivo de oferta encriptado', ['id' => $documento->id]);
            return $encryptionService->decryptAndStream(
                $filePath,
                $downloadFileName,
                $media->mime_type
            );
        }

        // Por defecto, descarga normal
        Log::info('Descargando archivo de oferta sin encriptar', ['id' => $documento->id]);
        return response()->download($filePath, $downloadFileName);
    }


    /**
     * Obtener documentos de la invitación del proveedor al concurso.
     * Incluye tanto documentos requeridos como adicionales.
     * Los documentos de oferta se incluyen todos sin restricción de fecha.
     */
    public function documentosInvitacion(Request $request, $concurso_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->with(['documentos.documentoTipo', 'documentos.creador'])
            ->firstOrFail();
        
        // Separar documentos requeridos y adicionales (sin filtro de fecha para documentos de oferta)
        $documentosRequeridos = $invitacion->documentos->whereNotNull('documento_tipo_id');
        $documentosAdicionales = $invitacion->documentos->whereNull('documento_tipo_id');
        
        return response()->json([
            'success' => true,
            'data' => [
                'documentos_requeridos' => OfertaDocumentoResource::collection($documentosRequeridos),
                'documentos_adicionales' => DocumentoAdicionalResource::collection($documentosAdicionales),
                'total_requeridos' => $documentosRequeridos->count(),
                'total_adicionales' => $documentosAdicionales->count(),
            ],
            'message' => 'Documentos de la invitación obtenidos correctamente.'
        ]);
    }

    /**
     * Obtener documentos adicionales de la invitación del proveedor al concurso.
     * Separa documentos de proveedor y de empresa.
     * Los documentos de oferta se incluyen todos sin restricción de fecha.
     */
    public function documentosAdicionales(Request $request, $concurso_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->with(['documentos' => function($q) {
                $q->whereNull('documento_tipo_id'); // Solo documentos adicionales
            }, 'documentos.creador'])
            ->firstOrFail();
        
        // Separar documentos de proveedor y de empresa (sin filtro de fecha para documentos de oferta)
        $documentosProveedor = $invitacion->documentos->whereNull('user_id_created');
        $documentosEmpresa = $invitacion->documentos->whereNotNull('user_id_created');
        
        return response()->json([
            'success' => true,
            'data' => [
                'documentos_proveedor' => DocumentoAdicionalResource::collection($documentosProveedor),
                'documentos_empresa' => DocumentoAdicionalResource::collection($documentosEmpresa),
                'total_proveedor' => $documentosProveedor->count(),
                'total_empresa' => $documentosEmpresa->count(),
            ],
            'message' => 'Documentos adicionales obtenidos correctamente.'
        ]);
    }

    /**
     * Eliminar un documento de la oferta del proveedor.
     * No se puede eliminar si la fecha actual es igual o posterior a la fecha de cierre del concurso.
     */
    public function eliminarDocumento(Request $request, $concurso_id, $documento_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        
        // Verificar que el proveedor tiene acceso al concurso
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->firstOrFail();
        
        // Obtener el concurso para verificar la fecha de cierre
        $concurso = $invitacion->concurso;
        
        // Verificar que no se haya pasado la fecha de cierre
        if (now()->gte($concurso->fecha_cierre)) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el documento. El concurso ya ha cerrado.'
            ], 403);
        }
        
        // Buscar el documento en la oferta del proveedor (puede ser requerido o adicional)
        $documento = OfertaDocumento::where('id', $documento_id)
            ->where('invitacion_id', $invitacion->id)
            ->first();
        
        if (!$documento) {
            return response()->json([
                'success' => false,
                'message' => 'Documento no encontrado en la oferta.'
            ], 404);
        }
        
        // Verificar que el documento fue subido por el proveedor (no por la empresa)
        if ($documento->user_id_created !== null) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un documento ingresado por la empresa.'
            ], 403);
        }
        
        DB::beginTransaction();
        try {
            // Eliminar el archivo físico usando Spatie Media Library
            $media = $documento->getFirstMedia('archivos');
            if ($media) {
                $media->delete();
            }
            
            // Eliminar el registro del documento
            $documento->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Documento eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dar de baja una oferta completa del proveedor.
     * Elimina todos los documentos y cambia la intención a 1 (con intención).
     */
    public function darBajaOferta(Request $request, $concurso_id)
    {
        $proveedor_id = $request->attributes->get('proveedor_id');
        
        // Verificar que el proveedor tiene acceso al concurso
        $invitacion = Invitacion::where('concurso_id', $concurso_id)
            ->where('proveedor_id', $proveedor_id)
            ->firstOrFail();
        
        // Obtener el concurso para verificar la fecha de cierre
        $concurso = $invitacion->concurso;
        
        // Verificar que no se haya pasado la fecha de cierre
        if (now()->gte($concurso->fecha_cierre)) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede dar de baja la oferta. El concurso ya ha cerrado.'
            ], 403);
        }
        
        DB::beginTransaction();
        try {
            // Obtener todos los documentos de la oferta que fueron subidos por el proveedor
            $documentos = OfertaDocumento::where('invitacion_id', $invitacion->id)
                ->whereNull('user_id_created') // Solo documentos subidos por el proveedor
                ->get();
            
            // Eliminar archivos físicos y registros de documentos
            foreach ($documentos as $documento) {
                $media = $documento->getFirstMedia('archivos');
                if ($media) {
                    $media->delete();
                }
                $documento->delete();
            }
            
            // Cambiar la intención a 1 (con intención)
            $invitacion->intencion = 1;
            $invitacion->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Oferta dada de baja correctamente. Se eliminaron ' . $documentos->count() . ' documentos.',
                'data' => [
                    'documentos_eliminados' => $documentos->count(),
                    'intencion_actualizada' => 1
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al dar de baja la oferta: ' . $e->getMessage()
            ], 500);
        }
    }


} 