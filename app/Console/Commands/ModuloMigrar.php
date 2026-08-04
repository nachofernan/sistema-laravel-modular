<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Corre las migraciones de un módulo, de a un archivo por vez, contra la base
 * exclusiva de ese módulo.
 *
 * A diferencia de `module:migrate-all`, este comando **nunca** pasa `--force`:
 * exige que el entorno sea local y muestra qué va a correr antes de tocar nada.
 * La idea es poder aplicar una tanda de migraciones nuevas sobre una base con
 * datos reales (un snapshot de producción) viendo el resultado de cada paso.
 */
class ModuloMigrar extends Command
{
    protected $signature = 'modulo:migrar
                            {modulo : Módulo cuya base se migra (ej: documentos)}
                            {--desde= : Corre desde esta migración en adelante (nombre del archivo o su prefijo)}
                            {--simular : Muestra el SQL de cada migración sin ejecutarla}';

    protected $description = 'Corre las migraciones de un módulo, de a un archivo por vez, contra su propia base. Sólo en entorno local.';

    public function handle(): int
    {
        $modulo = strtolower($this->argument('modulo'));

        if (! $this->entornoEsSeguro($modulo)) {
            return self::FAILURE;
        }

        $directorio = $this->directorioDeMigraciones($modulo);

        if ($directorio === null) {
            $this->error("No existe la carpeta de migraciones del módulo '{$modulo}' en database/migrations.");

            return self::FAILURE;
        }

        if (config("database.connections.{$modulo}") === null) {
            $this->error("No hay una conexión '{$modulo}' en config/database.php.");

            return self::FAILURE;
        }

        $migraciones = $this->migracionesACorrer($directorio);

        if ($migraciones === null) {
            return self::FAILURE;
        }

        if ($migraciones === []) {
            $this->info('No hay migraciones para correr.');

            return self::SUCCESS;
        }

        if (! $this->confirmarPlan($modulo, $directorio, $migraciones)) {
            $this->comment('Cancelado. No se tocó nada.');

            return self::SUCCESS;
        }

        return $this->correr($modulo, $directorio, $migraciones);
    }

    /**
     * Este comando no existe para producción: no pasa `--force` y se planta si el
     * entorno no es local o si la conexión apunta a una base que no es la de dev.
     */
    private function entornoEsSeguro(string $modulo): bool
    {
        if (! app()->isLocal()) {
            $this->error('Este comando corre sólo con APP_ENV=local (ahora: '.app()->environment().').');
            $this->line('No usa --force a propósito. Para migrar otro entorno, se hace explícitamente y a mano.');

            return false;
        }

        if (Str::endsWith($modulo, '_prod')) {
            $this->error("La conexión '{$modulo}' es la de producción. Se migra la base de dev del módulo, no esa.");

            return false;
        }

        $host = config("database.connections.{$modulo}.host");

        if ($host !== null && ! in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            $this->warn("La conexión '{$modulo}' apunta a {$host}, que no es esta máquina.");

            if (! $this->confirm('¿Seguro que querés migrar ahí?', false)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Las carpetas están en PascalCase (`Documentos`) y las conexiones en minúscula
     * (`documentos`); en Windows da igual, en un deploy Linux no.
     */
    private function directorioDeMigraciones(string $modulo): ?string
    {
        foreach (File::directories(database_path('migrations')) as $directorio) {
            if (strtolower(basename($directorio)) === $modulo) {
                return basename($directorio);
            }
        }

        return null;
    }

    /**
     * Devuelve los nombres de migración (sin `.php`) desde `--desde` en adelante, o
     * null si el `--desde` no corresponde a ningún archivo.
     */
    private function migracionesACorrer(string $directorio): ?array
    {
        $migraciones = collect(File::files(database_path("migrations/{$directorio}")))
            ->filter(fn ($archivo) => $archivo->getExtension() === 'php')
            ->map(fn ($archivo) => $archivo->getBasename('.php'))
            ->sort()
            ->values();

        $desde = $this->option('desde');

        if ($desde === null) {
            return $migraciones->all();
        }

        $desde = Str::of($desde)->basename('.php')->toString();

        if (! $migraciones->contains(fn ($migracion) => str_starts_with($migracion, $desde))) {
            $this->error("Ninguna migración de {$directorio} empieza con '{$desde}'.");

            return null;
        }

        // El nombre arranca con la fecha, así que ordenar y comparar como texto
        // alcanza para quedarse con esa migración y las posteriores.
        return $migraciones->filter(fn ($migracion) => $migracion >= $desde)->values()->all();
    }

    private function confirmarPlan(string $modulo, string $directorio, array $migraciones): bool
    {
        $aplicadas = $this->migracionesAplicadas($modulo);

        $this->newLine();
        $this->line("Módulo:    <fg=cyan>{$directorio}</>");
        $this->line("Conexión:  <fg=cyan>{$modulo}</> → base <fg=cyan>".config("database.connections.{$modulo}.database").'</> en '.config("database.connections.{$modulo}.host"));
        $this->newLine();

        $this->table(
            ['Migración', 'Estado'],
            array_map(fn ($migracion) => [
                $migracion,
                in_array($migracion, $aplicadas, true)
                    ? '<fg=gray>ya aplicada, se saltea</>'
                    : '<fg=yellow>pendiente</>',
            ], $migraciones)
        );

        $pendientes = count(array_diff($migraciones, $aplicadas));

        if ($pendientes === 0) {
            $this->info('Están todas aplicadas. No hay nada para correr.');

            return false;
        }

        if ($this->option('simular')) {
            $this->comment("Simulación: se muestra el SQL de {$pendientes} migración/es, sin ejecutarlo.");

            return true;
        }

        return $this->confirm("Se van a correr {$pendientes} migración/es, de a una. ¿Sigo?", true);
    }

    private function migracionesAplicadas(string $modulo): array
    {
        if (! Schema::connection($modulo)->hasTable('migrations')) {
            return [];
        }

        return DB::connection($modulo)->table('migrations')->pluck('migration')->all();
    }

    /**
     * Una llamada a `migrate` por archivo: si una falla, se corta ahí y las
     * anteriores quedan aplicadas, con su propio batch para poder revertirlas sueltas.
     */
    private function correr(string $modulo, string $directorio, array $migraciones): int
    {
        $aplicadas = $this->migracionesAplicadas($modulo);
        $corridas = 0;

        foreach ($migraciones as $migracion) {
            if (in_array($migracion, $aplicadas, true)) {
                continue;
            }

            $this->newLine();
            $this->line("<fg=yellow>→</> {$migracion}");

            $resultado = $this->call('migrate', [
                '--database' => $modulo,
                '--path' => "database/migrations/{$directorio}/{$migracion}.php",
                '--pretend' => (bool) $this->option('simular'),
            ]);

            if ($resultado !== 0) {
                $this->newLine();
                $this->error("Falló {$migracion}. Se corta acá: las anteriores quedaron aplicadas.");

                return self::FAILURE;
            }

            $corridas++;
        }

        $this->newLine();

        $this->option('simular')
            ? $this->info("Simulación terminada: {$corridas} migración/es.")
            : $this->info("Listo: {$corridas} migración/es aplicadas en la base del módulo {$directorio}.");

        return self::SUCCESS;
    }
}
