<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrarDocumentos extends Command
{
    protected $signature = 'documentos:migrar';

    protected $description = 'Corre las migraciones del módulo Documentos posteriores al 2026_07_04 en la base documentos.';

    public function handle(): int
    {
        // Sin --force: si no es local, no corre.
        if (! app()->isLocal()) {
            $this->error('Sólo en local (ahora: '.app()->environment().').');

            return self::FAILURE;
        }

        $migraciones = collect(File::files(database_path('migrations/Documentos')))
            ->map(fn ($archivo) => $archivo->getBasename('.php'))
            ->filter(fn ($nombre) => $nombre > '2026_07_04')
            ->sort();

        foreach ($migraciones as $migracion) {
            $this->call('migrate', [
                '--database' => 'documentos',
                '--path' => "database/migrations/Documentos/{$migracion}.php",
            ]);
        }

        return self::SUCCESS;
    }
}
