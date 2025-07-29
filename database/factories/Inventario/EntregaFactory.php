<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Entrega;
use App\Models\Inventario\Elemento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntregaFactory extends Factory
{
    protected $model = Entrega::class;

    public function definition()
    {
        return [
            'elemento_id' => Elemento::factory(),
            'user_id' => User::factory(),
            'fecha_entrega' => $this->faker->date(),
            'fecha_firma' => $this->faker->dateTime(),
            'fecha_devolucion' => $this->faker->date(),
        ];
    }
} 