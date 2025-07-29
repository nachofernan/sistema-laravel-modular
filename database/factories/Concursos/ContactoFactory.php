<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\Contacto;
use App\Models\Concursos\Concurso;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactoFactory extends Factory
{
    protected $model = Contacto::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'correo' => $this->faker->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'tipo' => $this->faker->randomElement(['administrativo','tecnico']),
            'concurso_id' => Concurso::factory(),
        ];
    }
} 