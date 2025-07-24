<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tickets\Documento;
use Illuminate\Support\Facades\Storage;

class MigrarDocumentosTicketsASpatie extends Command
{
    protected $signature = 'tickets:migrar-a-spatie';
    protected $description = 'Migra los archivos actuales del módulo de tickets a Spatie Media Library';

    public function handle()
    {
        $this->info('Iniciando migración de documentos de tickets a Spatie Media Library...');
        $documentos = Documento::all();
        $migrados = 0;
        foreach ($documentos as $documento) {
            if ($documento->file_storage && Storage::disk('tickets')->exists($documento->file_storage)) {
                // Evitar duplicados
                if ($documento->getFirstMedia('archivos')) {
                    $this->line("Documento ID {$documento->id} ya tiene archivo en Spatie. Saltando...");
                    continue;
                }
                $ruta = Storage::disk('tickets')->path($documento->file_storage);
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
                $this->info("Documento ID {$documento->id} migrado correctamente.");
            } else {
                $this->warn("Documento ID {$documento->id} no tiene archivo físico. Saltando...");
            }
        }
        $this->info("Migración finalizada. Total migrados: $migrados");
    }
} 