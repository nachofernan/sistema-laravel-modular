<?php

namespace Database\Factories\Despacho;

use App\Models\Despacho\Registrador;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistradorFactory extends Factory
{
    protected $model = Registrador::class;

    public function definition(): array
    {
        return [
            'codigo' => strtoupper($this->faker->unique()->bothify('REG-###')),
            'nombre' => $this->faker->words(2, true),
            'tipo' => $this->faker->randomElement(['principal', 'respaldo', 'control', 'auxiliar', 'otro']),
            'tipo_dato' => $this->faker->randomElement(['pulsos', 'potencia']),
            'columna_datos' => $this->faker->numberBetween(1, 20),
            'factor_conversion' => $this->faker->randomFloat(6, 0.1, 10),
            'activo' => true,
            'notas' => $this->faker->optional()->sentence(),
        ];
    }
}
