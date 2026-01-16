<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncMediaTable extends Command
{
    protected $signature = 'sync:media-table';
    protected $description = 'Clona la tabla media de producción a la base de datos local de la plataforma';

    public function handle()
    {
        // Seguridad: No ejecutar en producción
        if (app()->environment('production')) {
            $this->error('Este comando NO puede ejecutarse en el entorno de producción.');
            return;
        }

        $this->info('Iniciando clonación de tabla media...');

        try {
            // Conexiones basadas en tu config/database.php
            $prodConn = DB::connection('plataforma_prod');
            $localConn = DB::connection('mysql'); // Tu conexión por defecto (plataforma_dev)

            // 1. Verificar conexión remota
            $prodConn->getPdo();
            
            // 2. Obtener total de registros
            $totalRecords = $prodConn->table('media')->count();
            
            if ($totalRecords === 0) {
                $this->warn('La tabla media en producción está vacía.');
                return;
            }

            $this->warn("Se eliminarán los registros locales y se copiarán {$totalRecords} registros.");
            
            if ($this->confirm('¿Estás seguro de continuar?', true)) {
                
                // Desactivar constraints para evitar errores de integridad temporal
                $localConn->statement('SET FOREIGN_KEY_CHECKS=0;');
                $localConn->table('media')->truncate();

                $bar = $this->output->createProgressBar($totalRecords);
                $bar->start();

                // Usamos chunk para no agotar la memoria de PHP
                $prodConn->table('media')->orderBy('id')->chunk(500, function ($records) use ($localConn, $bar) {
                    // Convertimos a array para el insert masivo
                    $data = $records->map(function ($record) {
                        return (array) $record;
                    })->toArray();

                    $localConn->table('media')->insert($data);
                    $bar->advance(count($data));
                });

                $bar->finish();
                $localConn->statement('SET FOREIGN_KEY_CHECKS=1;');

                $this->newLine();
                $this->info('✓ Tabla media sincronizada con éxito.');
            }

        } catch (\Exception $e) {
            $this->error('Error durante la sincronización: ' . $e->getMessage());
        }
    }
}