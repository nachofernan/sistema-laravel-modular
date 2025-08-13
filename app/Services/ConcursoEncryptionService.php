<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConcursoEncryptionService
{
    private $key;
    private $cipher = 'AES-256-CBC';
    private $chunkSize = 8192; // 8KB chunks para streaming

    public function __construct()
    {
        $this->key = base64_decode(config('app.concurso_encryption_key'));
        
        if (empty($this->key)) {
            throw new \Exception('CONCURSO_ENCRYPTION_KEY no está configurada en .env');
        }
        
        Log::info('ConcursoEncryptionService inicializado', [
            'key_length' => strlen($this->key),
            'key_hex_start' => bin2hex(substr($this->key, 0, 16)),
            'cipher' => $this->cipher
        ]);
    }

    /**
     * Encriptar un archivo usando streams
     */
    public function encryptFile($sourcePath, $destinationPath)
    {
        try {
            Log::info('Iniciando encryptFile', [
                'source_path' => $sourcePath,
                'destination_path' => $destinationPath,
                'key_hex_start' => bin2hex(substr($this->key, 0, 16))
            ]);
            
            $sourceHandle = fopen($sourcePath, 'rb');
            $destHandle = fopen($destinationPath, 'wb');
            
            if (!$sourceHandle || !$destHandle) {
                throw new \Exception('No se pudo abrir el archivo fuente o destino');
            }

            // Generar IV (Initialization Vector)
            $iv = random_bytes(openssl_cipher_iv_length($this->cipher));
            
            Log::info('IV generado para encriptación', [
                'iv_length' => strlen($iv),
                'iv_hex' => bin2hex($iv)
            ]);
            
            // Escribir IV al inicio del archivo encriptado
            fwrite($destHandle, $iv);

            // Leer todo el archivo y encriptarlo de una vez
            $fileContent = file_get_contents($sourcePath);
            
            Log::info('Archivo leído para encriptación', [
                'file_size' => strlen($fileContent)
            ]);
            
            $encryptedContent = openssl_encrypt($fileContent, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
            if ($encryptedContent === false) {
                Log::error('Error al encriptar archivo completo', [
                    'openssl_error' => openssl_error_string()
                ]);
                throw new \Exception('Error al encriptar archivo');
            }
            
            fwrite($destHandle, $encryptedContent);
            
            Log::info('Encriptación completada', [
                'original_size' => strlen($fileContent),
                'encrypted_size' => strlen($encryptedContent),
                'iv_used' => bin2hex($iv)
            ]);

            fclose($sourceHandle);
            fclose($destHandle);

            Log::info('Archivo encriptado exitosamente', [
                'source' => $sourcePath,
                'destination' => $destinationPath
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al encriptar archivo', [
                'source' => $sourcePath,
                'destination' => $destinationPath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Desencriptar un archivo usando streams
     */
    public function decryptFile($sourcePath, $destinationPath)
    {
        try {
            $sourceHandle = fopen($sourcePath, 'rb');
            $destHandle = fopen($destinationPath, 'wb');
            
            if (!$sourceHandle || !$destHandle) {
                throw new \Exception('No se pudo abrir el archivo fuente o destino');
            }

            // Leer IV del inicio del archivo
            $ivLength = openssl_cipher_iv_length($this->cipher);
            $iv = fread($sourceHandle, $ivLength);
            
            if (strlen($iv) !== $ivLength) {
                throw new \Exception('Archivo encriptado corrupto o inválido');
            }

            // Leer todo el archivo encriptado y desencriptarlo de una vez
            $encryptedContent = file_get_contents($sourcePath, false, null, $ivLength);
            
            Log::info('Archivo encriptado leído para desencriptación', [
                'encrypted_size' => strlen($encryptedContent)
            ]);
            
            $decryptedContent = openssl_decrypt($encryptedContent, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
            if ($decryptedContent === false) {
                Log::error('Error al desencriptar archivo completo', [
                    'openssl_error' => openssl_error_string()
                ]);
                throw new \Exception('Error al desencriptar archivo');
            }
            
            fwrite($destHandle, $decryptedContent);
            
            Log::info('Desencriptación completada', [
                'encrypted_size' => strlen($encryptedContent),
                'decrypted_size' => strlen($decryptedContent)
            ]);

            fclose($sourceHandle);
            fclose($destHandle);

            Log::info('Archivo desencriptado exitosamente', [
                'source' => $sourcePath,
                'destination' => $destinationPath
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al desencriptar archivo', [
                'source' => $sourcePath,
                'destination' => $destinationPath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Desencriptar y enviar archivo completo para descarga
     */
    public function decryptAndStream($encryptedPath, $filename, $mimeType)
    {
        try {
            Log::info('Iniciando decryptAndStream', [
                'encrypted_path' => $encryptedPath,
                'filename' => $filename,
                'mime_type' => $mimeType,
                'file_exists' => file_exists($encryptedPath),
                'file_size' => file_exists($encryptedPath) ? filesize($encryptedPath) : 'N/A'
            ]);
            
            // Leer IV del inicio del archivo
            $ivLength = openssl_cipher_iv_length($this->cipher);
            $iv = file_get_contents($encryptedPath, false, null, 0, $ivLength);
            
            Log::info('IV leído', [
                'iv_length' => $ivLength,
                'iv_actual_length' => strlen($iv),
                'iv_hex' => bin2hex($iv)
            ]);
            
            if (strlen($iv) !== $ivLength) {
                throw new \Exception('Archivo encriptado corrupto o inválido');
            }

            // Leer todo el contenido encriptado (sin el IV)
            $encryptedContent = file_get_contents($encryptedPath, false, null, $ivLength);
            
            Log::info('Contenido encriptado leído', [
                'encrypted_size' => strlen($encryptedContent)
            ]);
            
            // Desencriptar todo el contenido de una vez
            $decryptedContent = openssl_decrypt($encryptedContent, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
            if ($decryptedContent === false) {
                Log::error('Error al desencriptar archivo completo', [
                    'openssl_error' => openssl_error_string()
                ]);
                throw new \Exception('Error al desencriptar archivo');
            }
            
            Log::info('Archivo desencriptado exitosamente', [
                'decrypted_size' => strlen($decryptedContent)
            ]);

            // Crear respuesta con el contenido desencriptado
            return response($decryptedContent, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($decryptedContent),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al desencriptar archivo', [
                'path' => $encryptedPath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Desencriptar masivamente todos los documentos de un concurso
     */
    public function bulkDecryptOfertas($concursoId)
    {
        try {
            // Obtener todas las ofertas válidas (intención = 3) del concurso
            $invitaciones = \App\Models\Concursos\Invitacion::where('concurso_id', $concursoId)
                ->where('intencion', 3) // Oferta presentada
                ->with(['documentos' => function($q) {
                    $q->where('encriptado', true);
                }])
                ->get();

            $decryptedCount = 0;
            $errors = [];

            foreach ($invitaciones as $invitacion) {
                foreach ($invitacion->documentos as $documento) {
                    try {
                        // Obtener el media del documento
                        $media = $documento->getFirstMedia('archivos');
                        if (!$media) {
                            continue;
                        }

                        $encryptedPath = $media->getPath();
                        $decryptedPath = str_replace('.enc', '', $encryptedPath);

                        // Desencriptar archivo
                        $this->decryptFile($encryptedPath, $decryptedPath);

                        // Actualizar media para que apunte al archivo desencriptado
                        $media->update([
                            'file_name' => str_replace('.enc', '', $media->file_name),
                            'disk' => 'concursos_desencriptados'
                        ]);

                        // Marcar documento como desencriptado
                        $documento->update(['encriptado' => false]);

                        $decryptedCount++;

                        Log::info('Documento desencriptado en bulk', [
                            'documento_id' => $documento->id,
                            'concurso_id' => $concursoId
                        ]);

                    } catch (\Exception $e) {
                        $errors[] = [
                            'documento_id' => $documento->id,
                            'error' => $e->getMessage()
                        ];
                        
                        Log::error('Error al desencriptar documento en bulk', [
                            'documento_id' => $documento->id,
                            'concurso_id' => $concursoId,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            // Eliminar documentos de ofertas no presentadas
            $this->eliminarDocumentosOfertasNoPresentadas($concursoId);

            return [
                'success' => true,
                'decrypted_count' => $decryptedCount,
                'errors' => $errors
            ];

        } catch (\Exception $e) {
            Log::error('Error en desencriptación masiva', [
                'concurso_id' => $concursoId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Eliminar documentos de ofertas que no se presentaron formalmente
     */
    private function eliminarDocumentosOfertasNoPresentadas($concursoId)
    {
        try {
            // Obtener invitaciones con intención diferente a 3 (oferta presentada)
            $invitaciones = \App\Models\Concursos\Invitacion::where('concurso_id', $concursoId)
                ->where('intencion', '!=', 3)
                ->with(['documentos'])
                ->get();

            $deletedCount = 0;

            foreach ($invitaciones as $invitacion) {
                foreach ($invitacion->documentos as $documento) {
                    try {
                        // Eliminar archivo físico
                        $media = $documento->getFirstMedia('archivos');
                        if ($media) {
                            $media->delete();
                        }

                        // Eliminar registro de documento
                        $documento->delete();
                        $deletedCount++;

                        Log::info('Documento de oferta no presentada eliminado', [
                            'documento_id' => $documento->id,
                            'invitacion_id' => $invitacion->id,
                            'concurso_id' => $concursoId
                        ]);

                    } catch (\Exception $e) {
                        Log::error('Error al eliminar documento de oferta no presentada', [
                            'documento_id' => $documento->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            Log::info('Limpieza de ofertas no presentadas completada', [
                'concurso_id' => $concursoId,
                'deleted_count' => $deletedCount
            ]);

            return $deletedCount;

        } catch (\Exception $e) {
            Log::error('Error en limpieza de ofertas no presentadas', [
                'concurso_id' => $concursoId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Verificar si un archivo está encriptado
     */
    public function isEncrypted($filePath)
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            return false;
        }

        // Leer IV del inicio
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = fread($handle, $ivLength);
        fclose($handle);

        // Si el archivo es más pequeño que el IV, no está encriptado
        if (filesize($filePath) <= $ivLength) {
            return false;
        }

        // Verificar que el IV es válido (no es texto plano)
        return strlen($iv) === $ivLength && !ctype_print($iv);
    }
} 