<?php

namespace App\Http\Controllers\Encrypts;

use App\Http\Controllers\Controller;
use App\Services\FileEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    protected $fileEncryptionService;

    public function __construct(FileEncryptionService $fileEncryptionService)
    {
        $this->fileEncryptionService = $fileEncryptionService;
    }

    /**
     * Sube y encripta un archivo
     */
    public function upload(Request $request, ?string $disk = null)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        try {
            $result = $this->fileEncryptionService->encryptAndStore(
                $request->file('file'),
                'encrypted_files/',
                $disk
            );

            return response()->json([
                'message' => 'Archivo subido exitosamente',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al subir el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descarga un archivo desencriptado
     */
    public function download(Request $request, $filePath, ?string $disk = null)
    {
        try {
            $result = $this->fileEncryptionService->decrypt($filePath, $disk);
            
            return response()->streamDownload(
                function () use ($result) {
                    echo $result['content'];
                },
                $result['metadata']['originalName'],
                [
                    'Content-Type' => $result['metadata']['mimeType'],
                ]
            );

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al descargar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function decryptAndSave($filePath, ?string $disk = null)
    {
        try {
            //$name = explode('encrypted_files/', $filePath);
            $result = $this->fileEncryptionService->decryptAndSaveFile($filePath, basename($filePath), $disk);
            
            return response()->json([
               'message' => 'Archivo descargado y guardado exitosamente',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al descargar y guardar el archivo: '. $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descarga y elimina un archivo encriptado
     */
    public function downloadAndDelete(Request $request, $filePath)
    {
        try {
            $result = $this->fileEncryptionService->decryptAndDelete($filePath);
            
            return response()->streamDownload(
                function () use ($result) {
                    echo $result['content'];
                },
                $result['metadata']['originalName'],
                [
                    'Content-Type' => $result['metadata']['mimeType'],
                ]
            );

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al descargar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descarga y elimina un archivo encriptado
     */
    public function delete(Request $request, $filePath, $disk)
    {
        try {
            $result = $this->fileEncryptionService->delete($filePath, $disk);
            
            return response()->json(['resultado' => $result], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al descargar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }
}
