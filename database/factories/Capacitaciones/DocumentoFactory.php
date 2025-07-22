<?php

namespace Database\Factories\Capacitaciones;

use App\Models\Capacitaciones\Documento;
use App\Models\Capacitaciones\Capacitacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition()
    {
        return [
            'capacitacion_id' => Capacitacion::factory(),
            'nombre' => $this->faker->name(),
            'archivo' => $this->faker->filePath(),
            'file_storage' => $this->faker->filePath(),
            'extension' => $this->faker->fileExtension(),
            'mimeType' => $this->faker->mimeType(),
        ];
    }
} 