<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\Prorroga;
use App\Models\Concursos\Concurso;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProrrogaFactory extends Factory
{
    protected $model = Prorroga::class;

    public function definition()
    {
        return [
            'fecha_anterior' => $this->faker->dateTimeBetween('-2 months', '-1 month'),
            'fecha_actual' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'concurso_id' => Concurso::factory(),
        ];
    }
} 