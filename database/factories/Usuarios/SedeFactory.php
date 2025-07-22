<?php

namespace Database\Factories\Usuarios;

use App\Models\Usuarios\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

class SedeFactory extends Factory
{
    protected $model = Sede::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->city(),
        ];
    }
} 