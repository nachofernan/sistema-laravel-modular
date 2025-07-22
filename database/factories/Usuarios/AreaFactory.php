<?php

namespace Database\Factories\Usuarios;

use App\Models\Usuarios\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'area_id' => null, // Por defecto sin padre
        ];
    }
} 