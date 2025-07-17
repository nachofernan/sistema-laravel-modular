<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Encrypts\FileController as EncryptsFileController;
use App\Models\Concursos\Documento;
use App\Models\Concursos\DocumentoTipo;
use App\Models\Concursos\Invitacion;
use App\Models\Proveedores\Apoderado;
use App\Models\Proveedores\Proveedor;
use App\Services\FileEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use stdClass;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware('verify.jwt');
    }

    // Subida de archivos desde PortalProveedores
    public function upload(Request $request)
    {
        $invitacion = Invitacion::find($request->input('invitacion_id'));
        if(!$invitacion) {
            return response()->json([
                'message' => 'No se encontró la invitación',
            ], 400);
        } 
        if(
            !(
                // Caso 1: concurso abierto
                ($invitacion->concurso->estado->id == 2 && $invitacion->concurso->fecha_cierre > now())
                ||
                // Caso 2: en análisis solo si la intención es 3
                ($invitacion->concurso->estado->id == 3 && $invitacion->intencion == 3)
            )
        ) {
            return response()->json([
                'message' => 'El concurso no está abierto',
            ], 400);
        } 

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if($request->input('documento_tipo_id') > 0) {
                $documentoTipo = DocumentoTipo::find($request->input('documento_tipo_id'));
            } else {
                $documentoTipo = new stdClass();
                $documentoTipo->encriptado = false;
                $documentoTipo->id = null;
            }

            // Crear documento
            $documento = new Documento([
                'user_id_created' => null,
                'concurso_id' => null,
                'encriptado' => $documentoTipo->encriptado,
                'documento_tipo_id' => $documentoTipo->id,
                'invitacion_id' => $invitacion->id,
            ]);
            $documento->save();

            if($documentoTipo->encriptado) {
                // Lógica para archivos encriptados
                $fileController = new EncryptsFileController(app(FileEncryptionService::class));
                $encryptedFileResult = $fileController->upload($request, 'concursos');
                $encryptedFileData = $encryptedFileResult->getData();
                
                // Guardar metadatos del archivo encriptado
                $documento->archivo = $encryptedFileData->data->metadata->originalName;
                $documento->mimeType = $encryptedFileData->data->metadata->mimeType;
                $documento->extension = pathinfo($encryptedFileData->data->metadata->originalName, PATHINFO_EXTENSION);
                $documento->file_storage = $encryptedFileData->data->path;
            } else {
                // Lógica para archivos no encriptados con Spatie
                $media = $documento->addMediaFromRequest('file')
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('archivos');
                
                // Guardar metadatos en el modelo Documento
                $documento->archivo = $media->file_name;
                $documento->mimeType = $media->mime_type;
                $documento->extension = $media->getExtensionAttribute();
                $documento->file_storage = $media->getPath();
            }

            $documento->save();

            return response()->json([
                'invi' => $request->input('invitacion_id'),
                'docu' => $request->input('documento_tipo_id'),
                'message' => 'File uploaded successfully',
                'path' => $documento->file_storage,
            ], 200);
        }

        return response()->json([
            'message' => 'No file uploaded'
        ], 400);
    }

    // Descargar archivos solicitados por PortalProveedores
    public function download(Request $request)
    {
        
        $filename = $request->query('filename');
        $disk = $request->query('disk', 'proveedores'); // default a 'proveedores' si no se especifica
        
        // Validación de parámetros
        if (!$filename || !$disk) {
            return response()->json([
                'message' => 'Filename and disk are required'
            ], 400);
        }
        
        // Verificar si el disco existe
        if (!in_array($disk, array_keys(config('filesystems.disks')))) {
            return response()->json([
                'message' => 'Invalid disk specified',
            ], 400);
        }
        
        // Verificar si el archivo existe en el disco especificado
        if (!Storage::disk($disk)->exists($filename)) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        $encript = explode('/', $filename);
        if($encript[0] == 'encrypted_files') {
            // Obtener el archivo desencriptado
            $fileController = new EncryptsFileController(app(FileEncryptionService::class));
            return $fileController->download($request, $filename, $disk);
        } else {
            // Obtener la ruta completa del archivo
            $filePath = Storage::disk($disk)->path($filename);
            return Response::download($filePath);
        }
    }

    public function delete(Request $request)
    {

        
        $filename = $request->query('filename');
        $disk = $request->query('disk', 'proveedores'); // default a 'proveedores' si no se especifica
        
        $documento = Documento::where('file_storage', $filename)->first();
        if(!$documento) {
            return response()->json([
                'message' => 'No se encontró el documento en la base de datos',
            ], 400);
        } 

        $concurso = $documento->concurso ?? $documento->invitacion->concurso;

        if($concurso->estado->id != 2 || $concurso->fecha_cierre < now()) {
            return response()->json([
                'message' => 'El concurso no está abierto',
            ], 400);
        } 

        // Probador de json
        /* return response()->json([
            'message' => 'No se puede eliminar archivos',
            'request' => $request->all(),
        ], 400); */

        // Validación de parámetros
        if (!$filename || !$disk) {
            return response()->json([
                'message' => 'Filename and disk are required'
            ], 400);
        }

        // Verificar si el disco existe
        if (!in_array($disk, array_keys(config('filesystems.disks')))) {
            return response()->json([
                'message' => 'Invalid disk specified',
            ], 400);
        }

        $encript = explode('/', $filename);
        $respuesta = false;
        if($encript[0] == 'encrypted_files') {
            $fileController = new EncryptsFileController(app(FileEncryptionService::class));
            if($fileController->delete($request, $filename, $disk)) {
                $respuesta = true;
            }
        } else {
            if (Storage::disk($disk)->delete($filename)) {
                $respuesta = true;
            }
        }

        if($respuesta) {
            //$documento = Documento::where('file_storage', $filename)->first();
            $documento->delete();
            return response()->json([
                'message' => 'File deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to delete file'
            ], 500);
        }
        
    }

    public function uploadDocumentacionGeneral(Request $request)
    {
        // Validar que el archivo esté presente
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No se ha subido ningún archivo'], 400);
        }
        // Validar que el proveedor_id esté presente
        if (!$request->has('proveedor_id')) {
            return response()->json(['message' => 'El ID del proveedor es requerido'], 400);
        }
        // Validar que el documento_tipo_id esté presente
        if (!$request->has('documento_tipo_id')) {
            return response()->json(['message' => 'El ID del tipo de documento es requerido'], 400);
        }
        $proveedor = Proveedor::find($request->input('proveedor_id'));
        
        if (!$proveedor) {
            return response()->json(['message' => 'Proveedor no encontrado'], 404);
        }
        
        $documento = $proveedor->documentos()->create([
            'user_id_created' => 1, // o auth()->id() si hay autenticación de usuario API
            'vencimiento' => $request->input('vencimiento') ?? null,
            'documento_tipo_id' => $request->input('documento_tipo_id'),
        ]);

        if ($request->hasFile('file')) {
            $media = $documento->addMediaFromRequest('file')
                ->usingFileName($request->file('file')->getClientOriginalName())
                ->toMediaCollection('archivos');
            
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $media->getPath();
            $documento->save();
        }

        $documento->validacion()->create();

        return response()->json([
            'message' => 'Documento subido con éxito',
            'documento_id' => $documento->id,
            'path' => $documento->file_storage,
        ], 200);
    }

    public function uploadDocumentacionApoderado(Request $request)
    {
        // Validar que el archivo esté presente
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No se ha subido ningún archivo'], 400);
        }
        // Validar que el proveedor_id esté presente
        if (!$request->has('proveedor_id')) {
            return response()->json(['message' => 'El ID del proveedor es requerido'], 400);
        }
        // Validar que el documento_tipo_id esté presente
        if (!$request->has('tipo')) {
            return response()->json(['message' => 'El ID del tipo de documento es requerido'], 400);
        }
        $proveedor = Proveedor::find($request->input('proveedor_id'));
        
        if (!$proveedor) {
            return response()->json(['message' => 'Proveedor no encontrado'], 404);
        }

        $apoderado = Apoderado::create([
            'proveedor_id' => $request->input('proveedor_id'),
            'nombre' => $request->input('nombre') ?? null,
            'tipo' => $request->input('tipo'),
        ]);

        $documento = $apoderado->documentos()->create([
            'user_id_created' => 1,
            'vencimiento' => $request->all('vencimiento')['vencimiento'] ?? null,
        ]);

        if ($request->hasFile('file')) {
            $media = $documento->addMediaFromRequest('file')
                ->usingFileName($request->file('file')->getClientOriginalName())
                ->toMediaCollection('archivos');
            
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $media->getPath();
            $documento->save();
        }

        $documento->validacion()->create();
        //Mail::to('ifernandez@buenosairesenergia.com.ar')->send(new NuevoArchivoValidacion($documento->validacion));

        return response()->json([
            'message' => 'Documento subido con éxito',
            'documento_id' => $documento->id,
            'path' => $documento->file_storage,
        ], 200);
    }
}
