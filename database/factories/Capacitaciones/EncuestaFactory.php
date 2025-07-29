<?php

namespace Database\Factories\Capacitaciones;

use App\Models\Capacitaciones\Encuesta;
use App\Models\Capacitaciones\Capacitacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class EncuestaFactory extends Factory
{
    protected $model = Encuesta::class;

    public function definition()
    {
        return [
            'capacitacion_id' => Capacitacion::factory(),
            'estado' => 1,
            'nombre' => $this->faker->name(),
            'descripcion' => $this->faker->sentence(),
        ];
    }
} 