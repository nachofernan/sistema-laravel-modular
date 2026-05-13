<?php

namespace App\Console\Commands;

use App\Helpers\EmailHelper;
use App\Models\Concursos\Concurso;
use App\Models\Usuarios\ManagedJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LimpiarYReprogramarConcursos extends Command
{
    protected $signature = 'mail:reset-concursos {--force : Saltar confirmación}';

    protected $description = 'Cancela todos los jobs y managed_jobs pendientes de concursos y reprograma desde cero';

    public function handle()
    {
        // Estado actual
        $pendientesManagedJobs = ManagedJob::where('entity_type', 'concurso')
            ->where('status', 'pending')
            ->count();

        $jobsEnCola = DB::table('jobs')
            ->whereIn('queue', ['emails', 'emails-priority'])
            ->count();

        $concursosActivos = Concurso::where('estado_id', 2)->count();

        $this->info("Estado actual:");
        $this->table(
            ['', 'Cantidad'],
            [
                ['managed_jobs pending', $pendientesManagedJobs],
                ['Jobs en cola emails', $jobsEnCola],
                ['Concursos activos', $concursosActivos],
            ]
        );

        if (!$this->option('force') && !$this->confirm('¿Confirmar limpieza y reprogramación?')) {
            $this->info('Operación cancelada.');
            return;
        }

        // 1. Cancelar todos los managed_jobs pending de concursos
        $this->info('Cancelando managed_jobs pendientes...');
        $cancelados = ManagedJob::where('entity_type', 'concurso')
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);
        $this->info("  → {$cancelados} managed_jobs cancelados.");

        // 2. Vaciar la cola de emails completa
        $this->info('Vaciando cola de emails...');
        $eliminados = DB::table('jobs')
            ->whereIn('queue', ['emails', 'emails-priority'])
            ->delete();
        $this->info("  → {$eliminados} jobs eliminados de la cola.");

        // 3. Reprogramar todos los concursos activos
        $this->info('Reprogramando concursos activos...');
        $concursos = Concurso::where('estado_id', 2)->get();
        $bar = $this->output->createProgressBar($concursos->count());
        $bar->start();

        $jobsCreados = 0;
        foreach ($concursos as $concurso) {
            $correos = $concurso->getCorreosInteresados([
                'proveedores',
                'contactos_concurso',
                'contactos_proveedores'
            ]);

            $jobs = EmailHelper::programarEmailsAutomaticosConcurso($concurso, $correos);
            $jobsCreados += count($jobs);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Estado final
        $nuevosManagedJobs = ManagedJob::where('entity_type', 'concurso')
            ->where('status', 'pending')
            ->count();

        $nuevosEnCola = DB::table('jobs')
            ->whereIn('queue', ['emails', 'emails-priority'])
            ->count();

        $this->info('Estado final:');
        $this->table(
            ['', 'Cantidad'],
            [
                ['managed_jobs pending', $nuevosManagedJobs],
                ['Jobs en cola emails', $nuevosEnCola],
            ]
        );

        $this->info('Listo. Reiniciá el worker: php artisan queue:restart');
    }
}
