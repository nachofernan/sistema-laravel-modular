<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Concursos\Documento;
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
        $zipFileName = 'documentos_' . $this->invitacion->id . '_' . now()->format('YmdHis') . '.zip';
        $zipPath = $tempDir . '/' . $zipFileName;

        // Depuración de permisos y existencia de directorio
        if (!is_writable($tempDir)) {
            Log::error('Directorio temporal no es escribible: ' . $tempDir);
            return back()->with('error', 'Error de permisos de escritura');
        }

        $zip = new ZipArchive();
        $resultado = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($resultado === TRUE) {
            /* // Obtener todos los documentos de la invitación
            $documentos = $this->invitacion->documentos;
            
            foreach ($documentos as $documento) {
                // Verificar existencia del archivo
                $filePath = Storage::disk('concursos')->path($documento->file_storage);
                
                if (file_exists($filePath)) {
                    // Usar nombre descriptivo en el ZIP
                    $nombreEnZip = $documento->nombre ? $documento->nombre : 'documento_' . $documento->id . '.pdf';
                    $zip->addFile($filePath, $nombreEnZip);
                } else {
                    Log::warning('Archivo no encontrado: ' . $filePath);
                }
            } */
            $documentosTiposIncluidos = [];

            // Documentos específicos de la invitación (prioridad máxima)
            foreach ($this->invitacion->documentos as $documento) {
                // Solo incluir si es válido para la oferta (validado, no vencido, cargado antes del cierre)
                if (method_exists($documento, 'esValidoParaOferta') && $documento->esValidoParaOferta($this->concurso->fecha_cierre)) {
                    $filePath = Storage::disk('concursos')->path($documento->file_storage);
                    if (file_exists($filePath)) {
                        if($documento->documento_tipo_id) {
                            $tipo_documento = Str::slug($documento->documentoTipo->nombre, '_');
                        } else {
                            $tipo_documento = 'otros_documentos';
                            if($documento->user_id_created) {
                                $tipo_documento .= '_baesa';
                            }
                        }
                        $nombreEnZip = $documento->nombre ? $documento->nombre : $tipo_documento . '_' . $documento->id . '.pdf';
                        $zip->addFile($filePath, 'Concurso_' . $nombreEnZip);
                        $documentosTiposIncluidos[] = $documento->documento_tipo_id;
                    }
                }
            }
    
            // Documentos del proveedor para completar
            foreach ($this->concurso->documentos_requeridos as $documentoTipo) {
                // Si ya no está incluido
                if (!in_array($documentoTipo->id, $documentosTiposIncluidos)) {
                    if ($documentoTipo->tipo_documento_proveedor) {
                        // Usar el nuevo método para traer el documento válido a la fecha de cierre
                        $documentoProveedor = $this->invitacion->proveedor->traer_documento_valido($documentoTipo->tipo_documento_proveedor->id, $this->concurso->fecha_cierre);
                        if ($documentoProveedor && file_exists(Storage::disk('proveedores')->path($documentoProveedor->file_storage))) {
                            $filePath = Storage::disk('proveedores')->path($documentoProveedor->file_storage);
                            if (file_exists($filePath)) {
                                $tipo_documento = Str::slug($documentoProveedor->documentoTipo->nombre, '_');
                                $nombreEnZip = $documentoProveedor->nombre ? $documentoProveedor->nombre : $tipo_documento . '_' . $documentoProveedor->id . '.pdf';
                                $zip->addFile($filePath, 'Proveedor_' . $nombreEnZip);
                                $documentosTiposIncluidos[] = $documentoProveedor->documento_tipo_id;
                            }
                        }
                    }
                }
            }
            
            $zip->close();
            
            // Verificar que el ZIP se creó correctamente
            if (file_exists($zipPath)) {
                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            } else {
                Log::error('No se pudo crear el archivo ZIP');
                return back()->with('error', 'No se pudo crear el archivo de descarga');
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
