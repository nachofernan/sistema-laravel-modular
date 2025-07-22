<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Modificacion;
use App\Models\Inventario\Elemento;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModificacionFactory extends Factory
{
    protected $model = Modificacion::class;

    public function definition()
    {
        return [
            'elemento_id' => Elemento::factory(),
            'modificacion' => $this->faker->sentence(),
            'valor_nuevo' => $this->faker->sentence(),
        ];
    }
} 