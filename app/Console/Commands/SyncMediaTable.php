<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncMediaTable extends Command
{
    // El comando acepta un flag opcional para filtrar por "model_type" si solo quieres media de un módulo
    protected $signature = 'sync:media {--model= : Filtrar por tipo de modelo (ej. App\Models\User)}';
    protected $description = 'Sincroniza la tabla de Spatie Media de producción al entorno local';

    public function handle()
    {
        $environment = app()->environment();
        $modelFilter = $this->option('model');

        if ($environment === 'production') {
            $this->error('¡Peligro! No puedes correr este comando en producción.');
            return;
        }

        $this->info("Iniciando sincronización de tabla 'media' desde producción...");

        try {
            // 1. Verificar conexiones (Asegúrate de tener 'landlord_prod' o 'main_prod' en config/database.php)
            // Usaré 'main_prod' y 'mysql' (o tu conexión local principal)
            $prodConn = DB::connection('main_prod'); 
            $localConn = DB::connection('mysql'); 

            $query = $prodConn->table('media');
            
            if ($modelFilter) {
                $query->where('model_type', $modelFilter);
                $this->comment("Filtrando por modelo: {$modelFilter}");
            }

            $total = $query->count();

            if ($total === 0) {
                $this->warn('No se encontraron registros en la tabla media de producción.');
                return;
            }

            if ($this->confirm("Se borrarán los datos locales de la tabla 'media' y se copiarán {$total} registros. ¿Continuar?", true)) {
                
                // Desactivar FK y limpiar tabla local
                $localConn->statement('SET FOREIGN_KEY_CHECKS=0;');
                
                if (!$modelFilter) {
                    $localConn->table('media')->truncate();
                } else {
                    $localConn->table('media')->where('model_type', $modelFilter)->delete();
                }

                $bar = $this->output->createProgressBar($total);
                $bar->start();

                // Sincronizar en chunks para no saturar la memoria
                $query->orderBy('id')->chunk(500, function ($records) use ($localConn, $bar) {
                    $data = $records->map(function ($item) {
                        return (array) $item;
                    })->toArray();

                    $localConn->table('media')->insert($data);
                    $bar->advance(count($data));
                });

                $bar->finish();
                $localConn->statement('SET FOREIGN_KEY_CHECKS=1;');
                
                $this->newLine();
                $this->info('✓ Tabla media sincronizada correctamente.');
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}