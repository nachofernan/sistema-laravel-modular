<?php

namespace Database\Seeders\Proveedores;

use App\Models\Proveedores\Documento;
use App\Models\Proveedores\DocumentoTipo;
use App\Models\Proveedores\Proveedor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentoSeeder extends Seeder
{
    /**
     * Genera documentos ficticios sobre los proveedores ya sembrados, usando el esquema
     * polimórfico vigente (documentable_type/documentable_id). El seeder anterior usaba un
     * proveedor_id plano que ya no existe en la tabla — quedó reemplazado por completo.
     */
    public function run(): void
    {
        $usuarioSeeder = User::firstOrCreate(
            ['email' => 'seeder-bot@baesa.local'],
            User::factory()->raw(['name' => 'Seeder Bot', 'realname' => 'Seeder Bot', 'email' => 'seeder-bot@baesa.local'])
        );

        $tiposDocumento = DocumentoTipo::pluck('id')->all();

        Proveedor::inRandomOrder()->take(579)->get()->each(function (Proveedor $proveedor) use ($usuarioSeeder, $tiposDocumento) {
            $cantidad = fake()->numberBetween(1, 6);

            for ($i = 0; $i < $cantidad; $i++) {
                Documento::factory()->create([
                    'documentable_type' => Proveedor::class,
                    'documentable_id' => $proveedor->id,
                    'documento_tipo_id' => fake()->randomElement($tiposDocumento),
                    'user_id_created' => $usuarioSeeder->id,
                    'user_id_updated' => null,
                ]);
            }
        });
    }
}
