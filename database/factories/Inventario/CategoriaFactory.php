<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'prefijo' => $this->faker->lexify('CAT-???'),
        ];
    }
} 