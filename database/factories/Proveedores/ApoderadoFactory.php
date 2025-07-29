<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Apoderado;
use App\Models\Proveedores\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApoderadoFactory extends Factory
{
    protected $model = Apoderado::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'tipo' => $this->faker->randomElement(['representante','apoderado']),
            'activo' => $this->faker->boolean(),
            'proveedor_id' => Proveedor::factory(),
        ];
    }
} 