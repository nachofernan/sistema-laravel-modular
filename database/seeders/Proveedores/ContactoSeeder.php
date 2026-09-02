<?php

namespace Database\Seeders\Proveedores;

use App\Models\Proveedores\Contacto;
use App\Models\Proveedores\Proveedor;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactoSeeder extends Seeder
{
    /**
     * Genera contactos ficticios sobre una muestra de proveedores ya sembrados
     * (no todos los proveedores reales tenían contacto cargado, se replica esa proporción).
     */
    public function run(): void
    {
        $usuarioSeeder = User::firstOrCreate(
            ['email' => 'seeder-bot@baesa.local'],
            User::factory()->raw(['name' => 'Seeder Bot', 'realname' => 'Seeder Bot', 'email' => 'seeder-bot@baesa.local'])
        );

        Proveedor::inRandomOrder()->take(121)->get()->each(function (Proveedor $proveedor) use ($usuarioSeeder) {
            Contacto::factory()->create([
                'proveedor_id' => $proveedor->id,
                'user_id_created' => $usuarioSeeder->id,
                'user_id_updated' => null,
            ]);
        });
    }
}
