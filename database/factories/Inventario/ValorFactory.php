<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Valor;
use App\Models\Inventario\Elemento;
use App\Models\Inventario\Caracteristica;
use App\Models\Inventario\Opcion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ValorFactory extends Factory
{
    protected $model = Valor::class;

    public function definition()
    {
        return [
            'valor' => $this->faker->name(),
            'caracteristica_id' => Caracteristica::factory(),
            'elemento_id' => Elemento::factory(),
        ];
    }
} 