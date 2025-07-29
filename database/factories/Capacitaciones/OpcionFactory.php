<?php

namespace Database\Factories\Capacitaciones;

use App\Models\Capacitaciones\Opcion;
use App\Models\Capacitaciones\Pregunta;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpcionFactory extends Factory
{
    protected $model = Opcion::class;
    protected $connection = 'capacitaciones';

    public function definition()
    {
        return [
            'opcion' => $this->faker->name(),
            'pregunta_id' => Pregunta::factory(),
        ];
    }
} 