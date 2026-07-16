<?php

namespace Database\Factories\Usuarios;

use App\Models\Usuarios\TipoArea;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoAreaFactory extends Factory
{
    protected $model = TipoArea::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->word(),
            'orden' => 0,
        ];
    }
}
