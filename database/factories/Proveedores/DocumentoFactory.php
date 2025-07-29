<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Apoderado;
use App\Models\Proveedores\Documento;
use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\DocumentoTipo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition()
    {
        // Elegimos aleatoriamente el tipo de documentable
        $tipo = $this->faker->randomElement(['proveedor', 'apoderado']);
        if ($tipo === 'proveedor') {
            $documentable = Proveedor::factory();
            $documentableType = Proveedor::class;
            $documentoTipoId = DocumentoTipo::factory();
        } else {
            $documentable = Apoderado::factory();
            $documentableType = Apoderado::class;
            $documentoTipoId = null;
        }

        return [
            'archivo' => $this->faker->word() . '.pdf',
            'file_storage' => $this->faker->uuid(),
            'extension' => 'pdf',
            'mimeType' => 'application/pdf',
            'vencimiento' => $this->faker->optional()->date(),
            'documentable_type' => $documentableType,
            'documentable_id' => $documentable,
            'documento_tipo_id' => $documentoTipoId,
            'user_id_created' => User::factory(),
            'user_id_updated' => User::factory(),
        ];
    }
} 