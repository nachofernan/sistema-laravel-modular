<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class FileEncryptionService
{
    protected $cipher = 'aes-256-gcm';
    protected $tempDirectory = 'encrypted_files';

    /**
     * Generate a secure encryption key
     * 
     * @return string
     */
    protected function generateKey()
    {
        return random_bytes(32); // 256-bit key
    }

    /**
     * Retrieve or generate a consistent encryption key
     * 
     * @return string
     */
    protected function getEncryptionKey()
    {
        $keyPath = storage_path('app/encryption_key');
        
        if (!File::exists($keyPath)) {
            $key = $this->generateKey();
            File::put($keyPath, base64_encode($key));
        } else {
            $key = base64_decode(File::get($keyPath));
        }
        
        return $key;
    }

    /**
     * Enhanced encryption with authenticated encryption (GCM mode)
     * 
     * @param UploadedFile|string $file
     * @param string $path
     * @param string|null $disk
     * @return array
     */
    public function encryptAndStore($file, string $path, ?string $disk = null)
    {
        try {
            // Prepare encryption parameters
            $key = $this->getEncryptionKey();
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
            $tag = null;

            // Get file input stream
            $inputStream = $file instanceof UploadedFile 
                ? fopen($file->getRealPath(), 'rb')
                : fopen($file, 'rb');

            $fileContent = stream_get_contents($inputStream);
            
            // Encrypt with GCM mode for better authentication
            $encryptedContent = openssl_encrypt(
                $fileContent, 
                $this->cipher, 
                $key, 
                OPENSSL_RAW_DATA, 
                $iv,
                $tag
            );

            // Prepare file name and path
            $fileName = $file instanceof UploadedFile 
                ? $file->hashName() 
                : basename($file);
            $finalPath = trim($path, '/') . '/' . $fileName;

            // Store encrypted file with metadata
            $metadata = [
                'iv' => base64_encode($iv),
                'tag' => base64_encode($tag),
                'originalName' => $fileName,
                'mimeType' => $file instanceof UploadedFile 
                    ? $file->getMimeType() 
                    : mime_content_type($file)
            ];

            Storage::disk($disk)->put($finalPath, $encryptedContent);
            Storage::disk($disk)->put(
                $finalPath . '.meta', 
                json_encode($metadata)
            );

            return [
                'path' => $finalPath,
                'filename' => $fileName,
                'metadata' => $metadata
            ];

        } catch (Exception $e) {
            throw new Exception('File encryption failed: ' . $e->getMessage());
        }
    }

    /**
     * Enhanced decryption with authentication verification
     * 
     * @param string $path
     * @param string|null $disk
     * @return array
     */
    public function decrypt(string $path, ?string $disk = null)
    {
        try {
            // Retrieve key
            $key = $this->getEncryptionKey();

            // Load metadata
            $metadataPath = $path . '.meta';
            if (!Storage::disk($disk)->exists($metadataPath)) {
                throw new Exception('Metadata file not found');
            }

            $metadata = json_decode(
                Storage::disk($disk)->get($metadataPath), 
                true
            );

            // Prepare decryption parameters
            $iv = base64_decode($metadata['iv']);
            $tag = base64_decode($metadata['tag']);
            $encryptedContent = Storage::disk($disk)->get($path);

            // Decrypt with authentication
            $decryptedContent = openssl_decrypt(
                $encryptedContent, 
                $this->cipher, 
                $key, 
                OPENSSL_RAW_DATA, 
                $iv, 
                $tag
            );

            if ($decryptedContent === false) {
                throw new Exception('Decryption failed. File may have been tampered with.');
            }

            return [
                'content' => $decryptedContent,
                'metadata' => $metadata
            ];

        } catch (Exception $e) {
            throw new Exception('File decryption failed: ' . $e->getMessage());
        }
    }

    /**
     * Decrypts a file and deletes the encrypted version
     * 
     * @param string $path Path to the encrypted file
     * @param string|null $disk Storage disk to use (optional)
     * @return array File content and metadata
     */
    public function decryptAndDelete(string $path, ?string $disk = null)
    {
        $result = $this->decrypt($path, $disk);
        
        // Delete encrypted file and its metadata
        Storage::disk($disk)->delete($path);
        Storage::disk($disk)->delete($path . '.meta');
        
        return $result;
    }

    /**
     * Decrypts a file and deletes the encrypted version
     * 
     * @param string $path Path to the encrypted file
     * @param string|null $disk Storage disk to use (optional)
     * @return array File content and metadata
     */
    public function delete(string $path, ?string $disk = null)
    {        
        // Delete encrypted file and its metadata
        Storage::disk($disk)->delete($path);
        Storage::disk($disk)->delete($path . '.meta');
        
        return true;
    }

    /**
     * Desencripta un archivo y lo guarda en su forma original
     * 
     * @param string $path Ruta del archivo encriptado
     * @param string $outputPath Ruta de salida para el archivo desencriptado
     * @param string|null $disk Disco de almacenamiento a utilizar
     * @return string Ruta del archivo desencriptado
     */
    public function decryptAndSaveFile(string $path, string $outputPath, ?string $disk = null)
    {
        // Obtener el contenido desencriptado
        $decryptedData = $this->decrypt($path, $disk);
        
        // Guardar el archivo desencriptado en la ruta especificada
        Storage::disk($disk ?? 'local')->put($outputPath, $decryptedData['content']);
        
        // Eliminar el archivo encriptado y sus metadatos
        Storage::disk($disk)->delete($path);
        Storage::disk($disk)->delete($path . '.meta');
        
        return $outputPath;
    }
}