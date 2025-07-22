<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Caracteristica;
use App\Models\Inventario\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CaracteristicaFactory extends Factory
{
    protected $model = Caracteristica::class;

    public function definition()
    {
        return [
            'categoria_id' => Categoria::factory(),
            'nombre' => $this->faker->word(),
        ];
    }
} 