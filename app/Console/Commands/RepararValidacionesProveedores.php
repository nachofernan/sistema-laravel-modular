<?php

namespace App\Console\Commands;

use App\Models\Proveedores\Documento;
use Illuminate\Console\Command;

class RepararValidacionesProveedores extends Command
{
    protected $signature = 'proveedores:reparar-validaciones';

    protected $description = 'Crea registros Validacion faltantes para documentos de Proveedores que no tienen uno';

    public function handle()
    {
        $documentos = Documento::whereDoesntHave('validacion')->get();

        if ($documentos->isEmpty()) {
            $this->info('Todo en orden: no hay documentos sin registro de validación.');
            return;
        }

        $this->warn("Se encontraron {$documentos->count()} documento(s) sin validación. Creando...");
        $bar = $this->output->createProgressBar($documentos->count());
        $bar->start();

        foreach ($documentos as $documento) {
            $documento->validacion()->create();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Listo. {$documentos->count()} registro(s) de validación creado(s).");
    }
}
