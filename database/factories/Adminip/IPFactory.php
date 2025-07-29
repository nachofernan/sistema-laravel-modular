<?php

namespace Database\Factories\Adminip;

use App\Models\Adminip\IP;
use Illuminate\Database\Eloquent\Factories\Factory;

class IPFactory extends Factory
{
    protected $model = IP::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'ip' => $this->faker->ipv4(),
            'bloque_a' => $this->faker->numberBetween(0, 255),
            'bloque_b' => $this->faker->numberBetween(0, 255),
            'bloque_c' => $this->faker->numberBetween(0, 255),
            'bloque_d' => $this->faker->numberBetween(0, 255),
        ];
    }
} 