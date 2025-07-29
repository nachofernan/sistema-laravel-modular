<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\DocumentoTipo;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoTipoFactory extends Factory
{
    protected $model = DocumentoTipo::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'descripcion' => $this->faker->optional()->sentence(),
            'de_concurso' => $this->faker->boolean(),
            'encriptado' => $this->faker->boolean(),
            'tipo_documento_proveedor_id' => null,
        ];
    }
} 