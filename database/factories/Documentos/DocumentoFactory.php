<?php

namespace Database\Factories\Documentos;

use App\Models\Documentos\Categoria;
use App\Models\Documentos\Documento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'codigo' => strtoupper($this->faker->lexify('??')).'-07.2-'.$this->faker->numberBetween(1, 999),
            'descripcion' => $this->faker->sentence(),
            'archivo' => $this->faker->word().'.pdf',
            'extension' => 'pdf',
            'mimeType' => 'application/pdf',
            'version' => $this->faker->numberBetween(1, 10),
            'orden' => $this->faker->numberBetween(1, 1000),
            'visible' => true,
            'publico' => true,
            'user_id' => User::factory(),
            'categoria_id' => Categoria::factory(),
        ];
    }

    /** Documento cargado pero fuera del portal público. */
    public function interno(): static
    {
        return $this->state(fn () => ['publico' => false]);
    }

    /** Documento dado de baja: se conserva, no se muestra. */
    public function oculto(): static
    {
        return $this->state(fn () => ['visible' => false, 'publico' => false]);
    }
}
