<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Rubro;
use Illuminate\Database\Eloquent\Factories\Factory;

class RubroFactory extends Factory
{
    protected $model = Rubro::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
        ];
    }
} 