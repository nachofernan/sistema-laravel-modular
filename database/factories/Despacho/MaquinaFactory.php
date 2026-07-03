<?php

namespace Database\Factories\Despacho;

use App\Models\Despacho\Maquina;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaquinaFactory extends Factory
{
    protected $model = Maquina::class;

    public function definition(): array
    {
        return [
            'codigo' => strtoupper($this->faker->unique()->bothify('MAQ-###')),
            'nombre' => $this->faker->words(2, true),
            'descripcion' => $this->faker->optional()->sentence(),
            'activa' => true,
        ];
    }
}
