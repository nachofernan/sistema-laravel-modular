<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\DocumentoTipo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoTipoFactory extends Factory
{
    protected $model = DocumentoTipo::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'codigo' => $this->faker->unique()->bothify('DOC-###'),
            'vencimiento' => $this->faker->boolean(),
            'user_id_validador' => User::factory(),
        ];
    }
} 