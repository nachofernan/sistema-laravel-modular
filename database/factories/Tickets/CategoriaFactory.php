<?php

namespace Database\Factories\Tickets;

use App\Models\Tickets\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
        ];
    }
} 