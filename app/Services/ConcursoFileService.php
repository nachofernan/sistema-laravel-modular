<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Concursos\Concurso; // Asumimos que existe este modelo
use App\Models\Oferta; // Asumimos que existe este modelo
use Illuminate\Support\Facades\Log;

class ConcursoFileService
{
    protected $chunkSize = 1024 * 1024; // 1MB por chunk
    protected $cipher = 'aes-256-cbc';

    public function encryptAndStoreOferta($file, Concurso $concurso, $proveedorId)
    {
        try {
            // Generar nombre estructurado para mejor organización
            $fileName = sprintf(
                'concurso_%d_proveedor_%d_%s_%s',
                $concurso->id,
                $proveedorId,
                uniqid(),
                $file->getClientOriginalName()
            );
            
            $tempPath = storage_path('app/temp/' . uniqid());
            
            if (!File::exists(storage_path('app/temp'))) {
                File::makeDirectory(storage_path('app/temp'), 0777, true);
            }

            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
            $key = openssl_random_pseudo_bytes(32);

            // Metadata específica para concursos
            $metadata = [
                'originalName' => $file->getClientOriginalName(),
                'mimeType' => $file->getMimeType(),
                'concursoId' => $concurso->id,
                'proveedorId' => $proveedorId,
                'uploadDate' => Carbon::now()->toDateTimeString(),
                'unlockDate' => $concurso->closing_date,
                'size' => $file->getSize(),
                'iv' => base64_encode($iv),
                'key' => base64_encode($key)
            ];

            // Guardar metadata
            $encryptedMetadata = encrypt($metadata);
            Storage::put("concursos/{$concurso->id}/ofertas/{$fileName}.meta", $encryptedMetadata);

            // Encriptar archivo
            $inputStream = fopen($file->getRealPath(), 'rb');
            $outputStream = fopen($tempPath, 'wb');

            while (!feof($inputStream)) {
                $chunk = fread($inputStream, $this->chunkSize);
                $encryptedChunk = openssl_encrypt(
                    $chunk,
                    $this->cipher,
                    $key,
                    OPENSSL_RAW_DATA,
                    $iv
                );
                fwrite($outputStream, $encryptedChunk);
            }

            fclose($inputStream);
            fclose($outputStream);

            // Mover a ubicación final
            Storage::disk('concursos')->put("{$concurso->id}/ofertas/{$fileName}", $tempPath);
            File::delete($tempPath);

            return [
                'fileName' => $fileName,
                'metadata' => $metadata
            ];

        } catch (\Exception $e) {
            if (isset($tempPath) && File::exists($tempPath)) {
                File::delete($tempPath);
            }
            Log::error('Error en oferta: ' . $e->getMessage());
            throw $e;
        }
    }

    public function canAccessFile($fileName, $userId)
    {
        try {
            $metadata = $this->getFileMetadata($fileName);
            
            // El proveedor siempre puede ver su propio archivo
            if ($metadata['proveedorId'] === $userId) {
                return true;
            }

            // Para otros usuarios, verificar fecha de cierre
            return Carbon::now()->gte(Carbon::parse($metadata['unlockDate']));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getFileMetadata($fileName)
    {
        $metaPath = $this->getMetaPath($fileName);
        if (!Storage::exists($metaPath)) {
            throw new \Exception('Metadata no encontrada');
        }
        return decrypt(Storage::get($metaPath));
    }

    public function batchDecryptConcursoFiles(Concurso $concurso)
    {
        try {
            if (Carbon::now()->lt($concurso->closing_date)) {
                throw new \Exception('El concurso aún no ha cerrado');
            }

            $decryptedPath = "concursos/{$concurso->id}/decrypted";
            Storage::makeDirectory($decryptedPath);
            
            $files = Storage::files("concursos/{$concurso->id}/ofertas");
            $files = array_filter($files, fn($f) => !str_ends_with($f, '.meta'));
            
            $results = [];
            foreach ($files as $file) {
                try {
                    $fileName = basename($file);
                    $metadata = $this->getFileMetadata($fileName);
                    
                    // Crear directorio por proveedor
                    $proveedorPath = "{$decryptedPath}/proveedor_{$metadata['proveedorId']}";
                    Storage::makeDirectory($proveedorPath);
                    
                    // Desencriptar y guardar
                    $decryptedContent = $this->decryptFile($fileName);
                    Storage::put(
                        "{$proveedorPath}/{$metadata['originalName']}", 
                        $decryptedContent
                    );
                    
                    $results[$fileName] = [
                        'status' => 'success',
                        'proveedor' => $metadata['proveedorId'],
                        'originalName' => $metadata['originalName']
                    ];
                    
                } catch (\Exception $e) {
                    $results[$fileName] = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            }
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('Error en desencriptación masiva: ' . $e->getMessage());
            throw $e;
        }
    }

    private function decryptFile($fileName)
    {
        $metadata = $this->getFileMetadata($fileName);
        $iv = base64_decode($metadata['iv']);
        $key = base64_decode($metadata['key']);
        
        $inputStream = Storage::readStream($this->getFilePath($fileName));
        $content = '';
        
        while (!feof($inputStream)) {
            $chunk = fread($inputStream, $this->chunkSize);
            if ($chunk === false) break;
            
            $decryptedChunk = openssl_decrypt(
                $chunk,
                $this->cipher,
                $key,
                OPENSSL_RAW_DATA,
                $iv
            );
            $content .= $decryptedChunk;
        }
        
        fclose($inputStream);
        return $content;
    }

    private function getFilePath($fileName)
    {
        $metadata = $this->getFileMetadata($fileName);
        return "concursos/{$metadata['concursoId']}/ofertas/{$fileName}";
    }

    private function getMetaPath($fileName)
    {
        return preg_replace('/^(.*?)(\.meta)?$/', '$1.meta', $this->getFilePath($fileName));
    }
}