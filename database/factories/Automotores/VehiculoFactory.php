<?php

namespace Database\Factories\Automotores;

use App\Models\Automotores\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehiculoFactory extends Factory
{
    protected $model = Vehiculo::class;

    public function definition(): array
    {
        return [
            'marca' => $this->faker->randomElement(['Ford', 'Chevrolet', 'Toyota', 'Volkswagen']),
            'modelo' => $this->faker->word(),
            'patente' => strtoupper($this->faker->unique()->bothify('??###??')),
            'kilometraje' => $this->faker->numberBetween(0, 150000),
        ];
    }
}
