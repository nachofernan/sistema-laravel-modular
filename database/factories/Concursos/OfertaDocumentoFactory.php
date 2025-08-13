<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\Invitacion;
use App\Models\Concursos\DocumentoTipo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concursos\OfertaDocumento>
 */
class OfertaDocumentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invitacion_id' => Invitacion::factory(),
            'documento_tipo_id' => DocumentoTipo::factory(),
            'documento_proveedor_id' => null,
            'archivo' => $this->faker->word() . '.pdf',
            'mimeType' => 'application/pdf',
            'extension' => 'pdf',
            'file_storage' => $this->faker->word() . '.pdf',
            'user_id_created' => null, // Por defecto subido por proveedor
            'validado' => $this->faker->boolean(80),
            'comentarios' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indica que el documento fue ingresado por un usuario de la empresa.
     */
    public function ingresadoPorEmpresa(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id_created' => User::factory(),
        ]);
    }

    /**
     * Indica que el documento es adicional (sin tipo específico).
     */
    public function adicional(): static
    {
        return $this->state(fn (array $attributes) => [
            'documento_tipo_id' => null,
        ]);
    }

    /**
     * Indica que el documento está validado.
     */
    public function validado(): static
    {
        return $this->state(fn (array $attributes) => [
            'validado' => true,
        ]);
    }
} 