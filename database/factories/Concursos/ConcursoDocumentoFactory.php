<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\Concurso;
use App\Models\Concursos\DocumentoTipo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concursos\ConcursoDocumento>
 */
class ConcursoDocumentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'archivo' => $this->faker->word() . '.pdf',
            'file_storage' => $this->faker->word() . '.pdf',
            'extension' => 'pdf',
            'mimeType' => 'application/pdf',
            'user_id_created' => User::factory(),
            'encriptado' => $this->faker->boolean(20),
            'concurso_id' => Concurso::factory(),
            'documento_tipo_id' => DocumentoTipo::factory(),
        ];
    }
} 