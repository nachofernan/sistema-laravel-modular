<?php

namespace App\Http\Controllers\Concursos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Encrypts\FileController;
use App\Mail\Concursos\NuevoDocumento;
use App\Models\Concursos\Concurso;
use App\Models\Concursos\ConcursoDocumento;
use App\Models\Concursos\OfertaDocumento;
use App\Models\Concursos\Invitacion;
use App\Services\FileEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
        // Determinar si es un documento del concurso o de oferta
        if ($request->input('concurso_id')) {
            // Es un documento del concurso
            $documento = new ConcursoDocumento([
                'user_id_created' => Auth::user()->id,
                'concurso_id' => $request->input('concurso_id'),
                'documento_tipo_id' => $request->input('documento_tipo_id'),
                'archivo' => 'x',
                'file_storage' => 'x',
            ]);
        } else {
            // Es un documento de oferta
            $documento = new OfertaDocumento([
                'invitacion_id' => $request->input('invitacion_id'),
                'documento_tipo_id' => $request->input('documento_tipo_id'),
                'documento_proveedor_id' => null,
                'user_id_created' => Auth::user()->id,
                'archivo' => 'x',
                'file_storage' => 'x',
                'encriptado' => false,
            ]);
        }

        if ($request->hasFile('file')) {
            $media = $documento->addMediaFromRequest('file')
                ->usingFileName($request->file('file')->getClientOriginalName())
                ->toMediaCollection('archivos');
            
            // Guardar metadatos en el modelo Documento
            $documento->archivo = $media->file_name;
            $documento->mimeType = $media->mime_type;
            $documento->extension = $media->getExtensionAttribute();
            $documento->file_storage = $request->file('file')->getClientOriginalName();
            $documento->save();
        }

        $concurso = $request->input('concurso_id') ? Concurso::find($request->input('concurso_id')) : Invitacion::find($request->input('invitacion_id'))->concurso;
        /* if($concurso->estado->id > 1) {
            $mails = [];
            foreach($concurso->invitaciones as $invitacion) {
                if($invitacion->intencion != 2) {
                    if(str_ends_with($invitacion->proveedor->correo, '@buenosairesenergia.com.ar')) {
                        $mails[] = $invitacion->proveedor->correo;
                    }
                    // $mails[] = $invitacion->proveedor->correo;
                }
            }
            foreach($mails as $mail) {
                Mail::to($mail)->send(new NuevoDocumento($documento));
            }
            
        } */

        // ESTO ES PARA ENCRIPTAAAAAAAAAAAAAAAAAAAAAAR!!!!!!!!

        /* if($request->input('documento_tipo_id') == 2) {
        } else {
            // Usar el FileEncryptionService para subir y encriptar el archivo
            $fileController = new FileController(app(FileEncryptionService::class));
            $encryptedFileResult = $fileController->upload($request, 'concursos');

            // Obtener los datos de respuesta del archivo encriptado
            $encryptedFileData = $encryptedFileResult->getData();

            // Crear el registro de documento con la información del archivo encriptado
            $documento = new Documento([
                'archivo' => $encryptedFileData->data->metadata->originalName,
                'mimeType' => $encryptedFileData->data->metadata->mimeType,
                'extension' => pathinfo($encryptedFileData->data->metadata->originalName, PATHINFO_EXTENSION),
                'file_storage' => $encryptedFileData->data->path,
                'user_id_created' => Auth::user()->id,
                'concurso_id' => $request->input('concurso_id'),
                'encriptado' => true, // Marcar como encriptado
                'documento_tipo_id' => $request->input('documento_tipo_id'),
            ]);
            $documento->save();
        } */

        return redirect()->route('concursos.concursos.show', $concurso)->with('info', 'Se creó con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show($documento_id)
    {
        // Buscar en ambas tablas
        $documento = ConcursoDocumento::find($documento_id);
        if (!$documento) {
            $documento = OfertaDocumento::find($documento_id);
        }
        
        if (!$documento) {
            abort(404, 'Documento no encontrado.');
        }
        
        // Verificar autorización para documentos de oferta
        if ($documento instanceof OfertaDocumento && $documento->invitacion->concurso->estado_id != 3) {
            abort(403, 'No autorizado');
        }
        
        $media = $documento->getFirstMedia('archivos');
        if ($media) {
            return $media->toResponse(request());
        }
        abort(404, 'Archivo no encontrado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($documento_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $documento_id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($documento_id)
    {
        // Buscar en ambas tablas
        $documento = ConcursoDocumento::find($documento_id);
        if (!$documento) {
            $documento = OfertaDocumento::find($documento_id);
        }
        
        if (!$documento) {
            abort(404, 'Documento no encontrado.');
        }
        
        // Obtener el concurso para la redirección
        if ($documento instanceof ConcursoDocumento) {
            $concurso = $documento->concurso;
        } else {
            $concurso = $documento->invitacion->concurso;
        }
        
        $documento->delete();
        return redirect()->route('concursos.concursos.show', $concurso)->with('info', 'Se eliminó con éxito');
    }

    public function downloadDocument($documento_id)
    {
        // Buscar en ambas tablas
        $documento = ConcursoDocumento::find($documento_id);
        if (!$documento) {
            $documento = OfertaDocumento::find($documento_id);
        }
        
        if (!$documento) {
            abort(404, 'Documento no encontrado.');
        }
        
        if (!$documento->encriptado) {
            // Lógica para documentos no encriptados con Spatie
            $media = $documento->getFirstMedia('archivos');
            if ($media) {
                return $media->toResponse(request());
            }
            abort(404, 'Archivo no encontrado.');
        }

        // Lógica para documentos encriptados
        $fileController = new FileController(app(FileEncryptionService::class));
        return $fileController->download(request(), $documento->file_storage, 'concursos');
    }
}
