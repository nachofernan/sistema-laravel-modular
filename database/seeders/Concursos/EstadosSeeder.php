<?php

namespace Database\Seeders\Concursos;

use App\Models\Concursos\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Estado::create([ 'id' => '1', 'nombre' => 'Iniciado', ]);
        Estado::create([ 'id' => '2', 'nombre' => 'En Curso', ]);
        Estado::create([ 'id' => '3', 'nombre' => 'Finalizado', ]);
    }
}
