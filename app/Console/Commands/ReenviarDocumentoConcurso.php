<?php

namespace App\Console\Commands;

use App\Jobs\Emails\EnviarCorreoAutomatizado;
use App\Mail\Concursos\NuevoDocumento;
use App\Models\Concursos\ConcursoDocumento;
use App\Models\Usuarios\ManagedJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReenviarDocumentoConcurso extends Command
{
    protected $signature = 'concursos:reenviar-documento
                            {concurso_id : ID del concurso}
                            {documento_id : ID del ConcursoDocumento}';

    protected $description = 'Reenvía la notificación de nuevo documento a destinatarios que no la recibieron';

    public function handle(): int
    {
        $concursoId  = (int) $this->argument('concurso_id');
        $documentoId = (int) $this->argument('documento_id');

        // 1. Cargar el documento
        $documento = ConcursoDocumento::find($documentoId);
        if (!$documento) {
            $this->error("No se encontró ConcursoDocumento con id {$documentoId}.");
            return self::FAILURE;
        }
        if ((int) $documento->concurso_id !== $concursoId) {
            $this->error("El documento #{$documentoId} no pertenece al concurso #{$concursoId}.");
            return self::FAILURE;
        }

        $this->info("Documento: #{$documento->id} — {$documento->archivo}");
        $this->info("Concurso:  #{$documento->concurso->numero} — {$documento->concurso->nombre}");
        $this->newLine();

        // 2. Managed jobs originales para esta notificación
        $managedJobs = ManagedJob::where('entity_type', 'concurso')
            ->where('entity_id', $concursoId)
            ->where('job_type', 'notificacion_nuevo_documento')
            ->get();

        if ($managedJobs->isEmpty()) {
            $this->error("No hay managed_jobs registrados para esta notificación en el concurso #{$concursoId}.");
            return self::FAILURE;
        }

        $this->line("Managed jobs registrados: <comment>{$managedJobs->count()}</comment>");

        // 3. Emails ya enviados exitosamente
        $yaEnviados = DB::table('email_logs')
            ->where('estado', 'exitoso')
            ->where('descripcion', 'like', "Nuevo documento - Concurso #{$documento->concurso->numero}%")
            ->pluck('destinatario')
            ->map(fn($e) => strtolower(trim($e)))
            ->toArray();

        $this->line("Ya enviados exitosamente: <comment>" . count($yaEnviados) . "</comment>");
        $this->newLine();

        // 4. Filtrar pendientes
        $pendientes = $managedJobs->filter(function ($job) use ($yaEnviados) {
            $email = strtolower(trim($job->metadata['destinatario'] ?? ''));
            return !empty($email) && !in_array($email, $yaEnviados);
        })->values();

        if ($pendientes->isEmpty()) {
            $this->info("Todos los destinatarios ya recibieron el correo. Nada que reenviar.");
            return self::SUCCESS;
        }

        // 5. Mostrar tabla de destinatarios pendientes
        $this->warn("Destinatarios que NO recibieron el correo ({$pendientes->count()}):");
        $this->table(
            ['#', 'Email', 'Nombre', 'Tipo', 'CUIT / Empresa'],
            $pendientes->map(function ($job, $i) {
                $ctx = $job->metadata['datos_contexto'] ?? [];
                return [
                    $i + 1,
                    $job->metadata['destinatario'] ?? '—',
                    $ctx['nombre']  ?? '—',
                    $ctx['tipo']    ?? '—',
                    $ctx['cuit']    ?? ($ctx['empresa'] ?? '—'),
                ];
            })->toArray()
        );

        $this->newLine();

        // 6. Confirmación
        if (!$this->confirm("¿Confirmar el reenvío a los {$pendientes->count()} destinatarios?")) {
            $this->info("Operación cancelada.");
            return self::SUCCESS;
        }

        // 7. Despachar jobs
        $this->newLine();
        $bar = $this->output->createProgressBar($pendientes->count());
        $bar->start();

        $proximoTiempo = Carbon::now()->addSeconds(5);
        $descripcion   = "Reenvío - Nuevo documento - Concurso #{$documento->concurso->numero}";

        foreach ($pendientes as $index => $managedJobRecord) {
            $email = $managedJobRecord->metadata['destinatario'];
            $data  = $managedJobRecord->metadata['datos_contexto'];

            $mailable = new NuevoDocumento($documento);
            $mailable->destinatario      = $email;
            $mailable->datosDestinatario = $data;

            $job = new EnviarCorreoAutomatizado(
                $email,
                $mailable,
                'programado',
                $descripcion
            );

            $job->delay($proximoTiempo->copy()->addSeconds(3 * $index));
            dispatch($job);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✓ {$pendientes->count()} correos despachados correctamente.");

        return self::SUCCESS;
    }
}
