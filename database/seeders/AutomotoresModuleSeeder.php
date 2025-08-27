<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuarios\Modulo;

class AutomotoresModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si el módulo ya existe
        $modulo = Modulo::where('nombre', 'Automotores')->first();
        
        if (!$modulo) {
            Modulo::create([
                'nombre' => 'Automotores',
                'descripcion' => 'Módulo para la gestión de vehículos y COPRES (compras de combustible)',
                'estado' => 'activo',
                'icono' => 'fas fa-car',
                'orden' => 10, // Ajustar según el orden deseado
                'color' => '#3B82F6', // Color azul
            ]);
            
            $this->command->info('Módulo Automotores creado exitosamente.');
        } else {
            $this->command->info('El módulo Automotores ya existe.');
        }
    }
}
