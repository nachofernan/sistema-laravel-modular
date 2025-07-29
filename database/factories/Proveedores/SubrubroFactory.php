<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Subrubro;
use App\Models\Proveedores\Rubro;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubrubroFactory extends Factory
{
    protected $model = Subrubro::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'rubro_id' => Rubro::factory(),
        ];
    }
} 