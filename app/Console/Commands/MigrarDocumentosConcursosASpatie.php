<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Concursos\ConcursoDocumento;
use App\Models\Concursos\OfertaDocumento;
use Illuminate\Support\Facades\Storage;

class MigrarDocumentosConcursosASpatie extends Command
{
    protected $signature = 'concursos:migrar-a-spatie';
    protected $description = 'Migra los archivos actuales del módulo de concursos a Spatie Media Library';

    public function handle()
    {
        $this->info('Iniciando migración de documentos de concursos a Spatie Media Library...');
        $documentos = ConcursoDocumento::all()->merge(OfertaDocumento::all());
        $migrados = 0;
        $errores = 0;
        $encriptados = 0;
        
        $bar = $this->output->createProgressBar($documentos->count());
        $bar->start();
        
        foreach ($documentos as $documento) {
            try {
                // Saltar documentos encriptados (se manejan por separado)
                if ($documento->encriptado) {
                    $this->line("\nDocumento ID {$documento->id} está encriptado. Saltando...");
                    $encriptados++;
                    continue;
                }

                if ($documento->file_storage && Storage::disk('concursos')->exists($documento->file_storage)) {
                    // Evitar duplicados
                    if ($documento->getFirstMedia('archivos')) {
                        $this->line("\nDocumento ID {$documento->id} ya tiene archivo en Spatie. Saltando...");
                        continue;
                    }
                    
                    $ruta = Storage::disk('concursos')->path($documento->file_storage);
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
        $this->info("Migración finalizada. Total migrados: $migrados, Errores: $errores, Encriptados: $encriptados");
    }
} 