<?php

namespace Database\Seeders\Proveedores;

use App\Models\Proveedores\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Estado::create([ 'id' => '1', 'estado' => 'Nivel 1', ]);
        Estado::create([ 'id' => '2', 'estado' => 'Nivel 2', ]);
        Estado::create([ 'id' => '3', 'estado' => 'Nivel 3', ]);
        Estado::create([ 'id' => '4', 'estado' => 'Eliminado', ]);
    }
}
