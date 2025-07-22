<?php

namespace Database\Factories\Documentos;

use App\Models\Documentos\Documento;
use App\Models\Documentos\Categoria;
use App\Models\User;
use App\Models\Usuarios\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'descripcion' => $this->faker->sentence(),
            'archivo' => $this->faker->filePath(),
            'file_storage' => $this->faker->filePath(),
            'extension' => $this->faker->fileExtension(),
            'mimeType' => $this->faker->mimeType(),
            'version' => $this->faker->numberBetween(1, 10),
            'orden' => $this->faker->numberBetween(1, 1000),
            'visible' => $this->faker->boolean(),
            'sede_id' => Sede::factory(),
            'user_id' => User::factory(),
            'categoria_id' => Categoria::factory(),
        ];
    }
} 