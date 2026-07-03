<?php

namespace Database\Factories\Automotores;

use App\Models\Automotores\Service;
use App\Models\Automotores\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'vehiculo_id' => Vehiculo::factory(),
            'kilometros' => $this->faker->numberBetween(0, 150000),
            'fecha_service' => $this->faker->date(),
        ];
    }
}
