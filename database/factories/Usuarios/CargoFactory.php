<?php

namespace Database\Factories\Usuarios;

use App\Models\Usuarios\Cargo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CargoFactory extends Factory
{
    protected $model = Cargo::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->jobTitle(),
            'orden' => 0,
        ];
    }
}
