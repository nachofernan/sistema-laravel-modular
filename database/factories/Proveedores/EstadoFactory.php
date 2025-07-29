<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstadoFactory extends Factory
{
    protected $model = Estado::class;

    public function definition()
    {
        return [
            'estado' => $this->faker->word(),
        ];
    }
} 