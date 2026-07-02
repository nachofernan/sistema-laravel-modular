<?php

namespace Database\Factories\Capacitaciones;

use App\Models\Capacitaciones\Capacitacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CapacitacionFactory extends Factory
{
    protected $model = Capacitacion::class;

    public function definition()
    {
        $inicio = $this->faker->dateTimeBetween('-1 month', '+1 month');
        return [
            'nombre' => $this->faker->sentence(3),
            'descripcion' => $this->faker->sentence(),
            'fecha_inicio' => $inicio->format('Y-m-d'),
            'fecha_final' => $this->faker->dateTimeBetween($inicio, '+2 months')->format('Y-m-d'),
        ];
    }
}
