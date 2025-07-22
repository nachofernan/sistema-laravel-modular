<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstadoFactory extends Factory
{
    protected $model = Estado::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
        ];
    }
} 