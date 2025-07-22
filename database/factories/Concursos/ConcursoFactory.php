<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\Concurso;
use App\Models\Concursos\Estado;
use App\Models\Concursos\Subrubro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConcursoFactory extends Factory
{
    protected $model = Concurso::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'numero' => $this->faker->optional()->randomNumber(6),
            'descripcion' => $this->faker->paragraph(),
            'fecha_inicio' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'fecha_cierre' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'numero_legajo' => $this->faker->bothify('LEG-####'),
            'legajo' => $this->faker->bothify('L-####'),
            'estado_id' => Estado::factory(),
            'subrubro_id' => null, // Se puede ajustar si hay factory de Subrubro
            'user_id' => User::factory(),
        ];
    }
} 