<?php

namespace Database\Seeders\Proveedores;

use App\Models\Proveedores\Estado;
use App\Models\Proveedores\Proveedor;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Genera proveedores ficticios (vía Faker) para entornos de desarrollo/testing.
     * Reemplaza el dataset real que tenía este seeder antes de sanitizar el repo.
     */
    public function run(): void
    {
        $usuarioSeeder = User::firstOrCreate(
            ['email' => 'seeder-bot@baesa.local'],
            User::factory()->raw(['name' => 'Seeder Bot', 'realname' => 'Seeder Bot', 'email' => 'seeder-bot@baesa.local'])
        );

        $estados = Estado::pluck('id')->all();

        Proveedor::factory()
            ->count(579)
            ->state(fn () => ['estado_id' => fake()->randomElement($estados)])
            ->create([
                'user_id_created' => $usuarioSeeder->id,
                'user_id_updated' => null,
            ]);
    }
}
