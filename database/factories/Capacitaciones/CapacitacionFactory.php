<?php

namespace Database\Factories\Capacitaciones;

use App\Models\Capacitaciones\Capacitacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CapacitacionFactory extends Factory
{
    protected $model = Capacitacion::class;
    protected $connection = 'capacitaciones';

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(),
            'descripcion' => $this->faker->sentence(),
            'fecha' => $this->faker->date(),
        ];
    }
} 