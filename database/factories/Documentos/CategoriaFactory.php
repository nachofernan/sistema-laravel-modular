<?php

namespace Database\Factories\Documentos;

use App\Models\Documentos\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'categoria_padre_id' => null,
        ];
    }
} 