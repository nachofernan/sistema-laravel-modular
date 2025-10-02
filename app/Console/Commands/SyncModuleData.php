<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncModuleData extends Command
{
    protected $signature = 'syncmoduledata {--module= : Module to sync}';
    protected $description = 'Sync module data from production to current environment';

    private $availableModules = [
        'usuarios', 'tickets', 'inventario', 'documentos', 
        'adminip', 'capacitaciones', 'proveedores', 
        'proveedores_externos', 'concursos', 'mesadeentradas', 'automotores'
    ];

    public function handle()
    {
        $module = $this->option('module');
        
        if (!$module) {
            $this->error('Please specify a module: --module=' . implode('|', $this->availableModules));
            return;
        }

        if (!in_array($module, $this->availableModules)) {
            $this->error("Module '{$module}' not found. Available: " . implode(', ', $this->availableModules));
            return;
        }

        $environment = app()->environment();
        
        if ($environment === 'production') {
            $this->error('Cannot run this command in production environment');
            return;
        }

        $this->info("Syncing module: {$module} from production to {$environment}");
        
        if (!$this->verifyConnections($module)) {
            return;
        }

        $this->syncModuleTables($module);
        
        $this->info('Sync completed successfully!');
    }

    private function verifyConnections($module)
    {
        try {
            // Verificar conexión a producción
            DB::connection("{$module}_prod")->getPdo();
            $this->info("✓ Connected to {$module} production database");
            
            // Verificar conexión local
            DB::connection($module)->getPdo();
            $this->info("✓ Connected to {$module} local database");
            
            return true;
        } catch (\Exception $e) {
            $this->error("Connection failed: " . $e->getMessage());
            return false;
        }
    }

    private function syncModuleTables($module)
    {
        // Obtener todas las tablas del módulo en producción
        $tables = DB::connection("{$module}_prod")
            ->select("SHOW TABLES");
        
        $tableKey = "Tables_in_" . DB::connection("{$module}_prod")->getDatabaseName();
        
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            $this->info("Syncing table: {$tableName}");
            
            if ($this->confirm("Sync table '{$tableName}'? (This will DELETE all current data)", true)) {
                $this->syncTable($tableName, $module);
            }
        }
    }

    private function syncTable($table, $module)
    {
        try {
            // Desactivar foreign key checks
            DB::connection($module)->statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Truncar tabla destino
            DB::connection($module)->table($table)->truncate();
            
            // Obtener total de registros
            $totalRecords = DB::connection("{$module}_prod")->table($table)->count();
            
            if ($totalRecords === 0) {
                $this->info("  └─ Table '{$table}' is empty, skipping");
                return;
            }
            
            $this->info("  └─ Copying {$totalRecords} records...");
            
            // Copiar datos en chunks
            $bar = $this->output->createProgressBar($totalRecords);
            $bar->start();
            
            // Detectar si la tabla tiene columna id
            $hasIdColumn = $this->tableHasIdColumn($table, $module);
            
            if ($hasIdColumn) {
                DB::connection("{$module}_prod")
                    ->table($table)
                    ->chunkById(500, function ($records) use ($table, $module, $bar) {
                        $data = $records->map(function ($record) {
                            return $this->sanitizeRecord((array) $record);
                        })->toArray();
                        
                        DB::connection($module)->table($table)->insert($data);
                        $bar->advance(count($data));
                    });
            } else {
                // Para tablas sin columna id, usar orderBy con la primera columna disponible
                $firstColumn = $this->getFirstColumn($table, $module);
                
                if ($firstColumn) {
                    DB::connection("{$module}_prod")
                        ->table($table)
                        ->orderBy($firstColumn)
                        ->chunk(500, function ($records) use ($table, $module, $bar) {
                            $data = $records->map(function ($record) {
                                return $this->sanitizeRecord((array) $record);
                            })->toArray();
                            
                            DB::connection($module)->table($table)->insert($data);
                            $bar->advance(count($data));
                        });
                } else {
                    // Si no se puede obtener una columna, usar get() y procesar en memoria
                    $records = DB::connection("{$module}_prod")->table($table)->get();
                    $data = $records->map(function ($record) {
                        return $this->sanitizeRecord((array) $record);
                    })->toArray();
                    
                    DB::connection($module)->table($table)->insert($data);
                    $bar->advance(count($data));
                }
            }
            
            $bar->finish();
            $this->newLine();
            
            // Reactivar foreign key checks
            DB::connection($module)->statement('SET FOREIGN_KEY_CHECKS=1;');
            
        } catch (\Exception $e) {
            $this->error("Error syncing table '{$table}': " . $e->getMessage());
        }
    }

    private function sanitizeRecord($record)
    {
        // Solo anonimizar en desarrollo
        if (app()->environment('local')) {
            if (isset($record['email'])) {
                $record['email'] = 'dev_' . $record['id'] . '@example.com';
            }
            if (isset($record['password'])) {
                $record['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
            }
        }
        
        return $record;
    }

    private function tableHasIdColumn($table, $module)
    {
        try {
            $columns = DB::connection("{$module}_prod")
                ->select("SHOW COLUMNS FROM {$table}");
            
            foreach ($columns as $column) {
                if ($column->Field === 'id') {
                    return true;
                }
            }
            
            return false;
        } catch (\Exception $e) {
            $this->warn("Could not check columns for table {$table}: " . $e->getMessage());
            return false; // Por defecto usar chunk simple si no se puede verificar
        }
    }

    private function getFirstColumn($table, $module)
    {
        try {
            $columns = DB::connection("{$module}_prod")
                ->select("SHOW COLUMNS FROM {$table}");
            
            if (!empty($columns)) {
                return $columns[0]->Field;
            }
            
            return null;
        } catch (\Exception $e) {
            $this->warn("Could not get columns for table {$table}: " . $e->getMessage());
            return null;
        }
    }
}