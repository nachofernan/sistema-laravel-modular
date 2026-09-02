<?php

namespace Database\Seeders\Proveedores;

use App\Models\Proveedores\Direccion;
use App\Models\Proveedores\Proveedor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DireccionSeeder extends Seeder
{
    /**
     * Genera direcciones ficticias sobre una muestra de proveedores ya sembrados
     * (no todos los proveedores reales tenían domicilio cargado, se replica esa proporción).
     */
    public function run(): void
    {
        $usuarioSeeder = User::firstOrCreate(
            ['email' => 'seeder-bot@baesa.local'],
            User::factory()->raw(['name' => 'Seeder Bot', 'realname' => 'Seeder Bot', 'email' => 'seeder-bot@baesa.local'])
        );

        Proveedor::inRandomOrder()->take(150)->get()->each(function (Proveedor $proveedor) use ($usuarioSeeder) {
            Direccion::factory()->create([
                'proveedor_id' => $proveedor->id,
                'user_id_created' => $usuarioSeeder->id,
                'user_id_updated' => null,
            ]);
        });
    }
}
