<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedores\Documento;
use Illuminate\Support\Facades\Storage;

class MigrarDocumentosProveedoresASpatie extends Command
{
    protected $signature = 'proveedores:migrar-a-spatie';
    protected $description = 'Migra los archivos actuales del módulo de proveedores a Spatie Media Library';

    public function handle()
    {
        $this->info('Iniciando migración de documentos de proveedores a Spatie Media Library...');
        $documentos = Documento::all();
        $migrados = 0;
        $errores = 0;
        
        $bar = $this->output->createProgressBar($documentos->count());
        $bar->start();
        
        foreach ($documentos as $documento) {
            try {
                if ($documento->file_storage && Storage::disk('proveedores')->exists($documento->file_storage)) {
                    // Evitar duplicados
                    if ($documento->getFirstMedia('archivos')) {
                        $this->line("\nDocumento ID {$documento->id} ya tiene archivo en Spatie. Saltando...");
                        continue;
                    }
                    $ruta = Storage::disk('proveedores')->path($documento->file_storage);
                    $media = $documento->addMedia($ruta)
                        ->usingFileName($documento->archivo)
                        ->toMediaCollection('archivos');
                    // Actualizar metadatos si es necesario
                    $documento->archivo = $media->file_name;
                    $documento->mimeType = $media->mime_type;
                    $documento->extension = $media->getExtensionAttribute();
                    $documento->file_storage = $media->getPath();
                    $documento->save();
                    $migrados++;
                } else {
                    $this->warn("\nDocumento ID {$documento->id} no tiene archivo físico. Saltando...");
                }
            } catch (\Exception $e) {
                $this->error("\nError migrando documento ID {$documento->id}: " . $e->getMessage());
                $errores++;
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        $this->info("Migración finalizada. Total migrados: $migrados, Errores: $errores");
    }
} 