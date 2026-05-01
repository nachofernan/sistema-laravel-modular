<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Concursos\Concurso;
use App\Helpers\EmailHelper;

class ReprogramarMailsConcursos extends Command
{
    // Nombre del comando para la terminal
    protected $signature = 'mail:reprogramar-concursos';
    
    protected $description = 'Regenera todos los emails programados para concursos activos con la nueva metadata';

    public function handle()
    {
        // 1. Buscamos solo los concursos que están en estado "Activo" (ID 2)
        $concursos = Concurso::where('estado_id', 2)->get();
        
        $this->info("Iniciando reprogramación de " . $concursos->count() . " concursos...");
        $bar = $this->output->createProgressBar($concursos->count());
        
        $bar->start();
        
        foreach ($concursos as $concurso) {
            // 2. Obtenemos los correos con la metadata completa (nombre, cuit, etc)
            $correos = $concurso->getCorreosInteresados([
                'proveedores', 
                'contactos_concurso', 
                'contactos_proveedores'
            ]);
            
            // 3. Usamos tu función de Helper que cancela lo viejo y crea lo nuevo
            EmailHelper::reprogramarEmailsConcurso($concurso, $correos);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->success("¡Proceso completado con éxito!");
    }
}
