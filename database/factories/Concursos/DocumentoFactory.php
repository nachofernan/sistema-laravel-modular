<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\Documento;
use App\Models\Concursos\Concurso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition()
    {
        return [
            'archivo' => $this->faker->word().'.pdf',
            'file_storage' => $this->faker->uuid(),
            'extension' => 'pdf',
            'mimeType' => 'application/pdf',
            'user_id_created' => User::factory(),
            'encriptado' => $this->faker->boolean(),
            'concurso_id' => Concurso::factory(),
        ];
    }
} 