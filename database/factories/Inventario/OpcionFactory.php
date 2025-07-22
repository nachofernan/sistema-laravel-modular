<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Opcion;
use App\Models\Inventario\Caracteristica;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpcionFactory extends Factory
{
    protected $model = Opcion::class;

    public function definition()
    {
        return [
            'caracteristica_id' => Caracteristica::factory(),
            'nombre' => $this->faker->word(),
        ];
    }
} 