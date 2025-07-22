<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Periferico;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerifericoFactory extends Factory
{
    protected $model = Periferico::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
} 