<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Concursos\OfertaDocumento;
use App\Models\Concursos\Invitacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use ZipArchive;

class VerOferta extends Component
{
    use WithFileUploads;

    public $open = false;
    public $concurso;
    public $invitacion;
    
    public function mount($concurso, $invitacion) {
        $this->concurso = $concurso;
        $this->invitacion = $invitacion;
    }

    public function descargarTodosDocumentos()
    {
        // Asegurar que el directorio temporal exista
        $tempDir = storage_path('app/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }
        
        // Nombre de archivo más predecible
        $zipFileName = 'oferta_' . $this->concurso->id . '_' . $this->invitacion->proveedor->id . '_' . now()->format('YmdHis') . '.zip';
        $zipPath = $tempDir . '/' . $zipFileName;

        // Depuración de permisos y existencia de directorio
        if (!is_writable($tempDir)) {
            Log::error('Directorio temporal no es escribible: ' . $tempDir);
            return back()->with('error', 'Error de permisos de escritura');
        }

        $zip = new ZipArchive();
        $resultado = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($resultado === TRUE) {
            $documentosAgregados = 0;
            
            // CRÍTICO: Para mantener consistencia auditable, solo se incluyen documentos del proveedor
            // que estaban válidos y cargados ANTES del cierre del concurso
            // Los documentos de oferta se incluyen todos sin restricción de fecha
            Log::info("Generando ZIP para oferta - Fecha de cierre: " . $this->concurso->fecha_cierre);

            // 1. Documentos de oferta (OfertaDocumento) - Prioridad máxima
            // Los documentos de oferta se incluyen todos, sin filtro de fecha, porque son parte de la oferta específica
            foreach ($this->invitacion->documentos as $documento) {
                $media = $documento->getFirstMedia('archivos');
                if ($media && $media->getPath()) {
                    // Determinar el tipo de documento
                    $tipoDocumento = 'otros';
                    if ($documento->documento_tipo_id && $documento->documentoTipo) {
                        $tipoDocumento = Str::slug($documento->documentoTipo->nombre, '_');
                    } elseif ($documento->user_id_created) {
                        $tipoDocumento = 'adicional_baesa';
                    } else {
                        $tipoDocumento = 'adicional_proveedor';
                    }
                    
                    // Limitar a 20 caracteres
                    $tipoDocumento = substr($tipoDocumento, 0, 20);
                    
                    // Generar nombre del archivo: Concurso-{tipo}-{id_media}
                    $nombreEnZip = 'Concurso-' . $tipoDocumento . '-' . $media->id . '.' . $media->getExtensionAttribute();
                    
                    $zip->addFile($media->getPath(), $nombreEnZip);
                    $documentosAgregados++;
                    Log::info("Documento de oferta agregado: {$nombreEnZip} (cargado: {$documento->created_at})");
                }
            }

            // 2. Documentos del proveedor para completar los requeridos
            foreach ($this->concurso->documentos_requeridos as $documentoTipo) {
                // Verificar si ya existe un documento de oferta para este tipo
                $documentoOfertaExistente = $this->invitacion->documentos()
                    ->where('documento_tipo_id', $documentoTipo->id)
                    ->first();
                
                // Solo agregar documento de proveedor si no hay documento de oferta
                if (!$documentoOfertaExistente && $documentoTipo->tipo_documento_proveedor) {
                    $documentoProveedor = $this->invitacion->proveedor->traer_documento_valido(
                        $documentoTipo->tipo_documento_proveedor->id, 
                        $this->concurso->fecha_cierre
                    );
                    
                    if ($documentoProveedor) {
                        $media = $documentoProveedor->getFirstMedia('archivos');
                        if ($media && $media->getPath()) {
                            // Obtener el nombre del tipo de documento del proveedor
                            $tipoDocumento = Str::slug($documentoProveedor->documentoTipo->nombre, '_');
                            $tipoDocumento = substr($tipoDocumento, 0, 20);
                            
                            // Generar nombre del archivo: Proveedor-{tipo}-{id_media}
                            $nombreEnZip = 'Proveedor-' . $tipoDocumento . '-' . $media->id . '.' . $media->getExtensionAttribute();
                            
                            $zip->addFile($media->getPath(), $nombreEnZip);
                            $documentosAgregados++;
                            Log::info("Documento de proveedor agregado: {$nombreEnZip} (cargado: {$documentoProveedor->created_at})");
                        }
                    }
                }
            }
            
            $zip->close();
            
            // Verificar que el ZIP se creó correctamente y tiene contenido
            if (file_exists($zipPath) && $documentosAgregados > 0) {
                Log::info("ZIP creado exitosamente con {$documentosAgregados} documentos");
                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            } else {
                Log::error('No se pudo crear el archivo ZIP o está vacío');
                return back()->with('error', 'No se encontraron documentos para descargar');
            }
        } else {
            // Depurar el error específico de ZipArchive
            switch($resultado) {
                case ZipArchive::ER_EXISTS:
                    $errorMsg = "El archivo ya existe";
                    break;
                case ZipArchive::ER_INCONS:
                    $errorMsg = "Archivo ZIP inconsistente";
                    break;
                case ZipArchive::ER_INVAL:
                    $errorMsg = "Parámetro inválido";
                    break;
                case ZipArchive::ER_MEMORY:
                    $errorMsg = "Error de memoria";
                    break;
                case ZipArchive::ER_NOENT:
                    $errorMsg = "No existe el archivo o directorio";
                    break;
                case ZipArchive::ER_NOZIP:
                    $errorMsg = "No es un archivo ZIP";
                    break;
                case ZipArchive::ER_READ:
                    $errorMsg = "Error de lectura";
                    break;
                case ZipArchive::ER_SEEK:
                    $errorMsg = "Error de búsqueda";
                    break;
                default:
                    $errorMsg = "Error desconocido al crear ZIP";
            }

            Log::error('Error al crear ZIP: ' . $errorMsg . ' (Código: ' . $resultado . ')');
            return back()->with('error', 'No se pudo crear el archivo ZIP: ' . $errorMsg);
        }
    }

    public function render()
    {
        return view('livewire.concursos.concurso.show.ver-oferta');
    }
}
