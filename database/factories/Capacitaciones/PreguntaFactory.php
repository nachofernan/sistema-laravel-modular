<?php

namespace Database\Factories\Capacitaciones;

use App\Models\Capacitaciones\Pregunta;
use App\Models\Capacitaciones\Encuesta;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreguntaFactory extends Factory
{
    protected $model = Pregunta::class;

    public function definition()
    {
        return [
            'pregunta' => $this->faker->sentence(),
            'con_opciones' => $this->faker->boolean(),
            'encuesta_id' => Encuesta::factory(),
        ];
    }
} 