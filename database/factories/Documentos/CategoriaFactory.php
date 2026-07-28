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
            'nombre' => $this->faker->words(2, true),
            'categoria_padre_id' => null,
            'publica' => true,
            'orden' => 0,
        ];
    }

    /** Categoría que no se muestra en el portal público. */
    public function interna(): static
    {
        return $this->state(fn () => ['publica' => false]);
    }

    public function hijaDe(Categoria $padre): static
    {
        return $this->state(fn () => ['categoria_padre_id' => $padre->id]);
    }
}
