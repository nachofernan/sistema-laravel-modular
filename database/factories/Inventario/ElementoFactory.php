<?php

namespace Database\Factories\Inventario;

use App\Models\Inventario\Elemento;
use App\Models\Inventario\Categoria;
use App\Models\Inventario\Estado;
use App\Models\User;
use App\Models\Usuarios\Area;
use App\Models\Usuarios\Sede;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElementoFactory extends Factory
{
    protected $model = Elemento::class;

    public function definition()
    {
        return [
            'codigo' => $this->faker->name(),
            'notas' => $this->faker->sentence(),

            'sede_id' => Sede::factory(),
            'area_id' => Area::factory(),
            'user_id' => User::factory(),
            'categoria_id' => Categoria::factory(),
            'estado_id' => Estado::factory(),
            
            'proveedor' => $this->faker->name(),
            'soporte' => $this->faker->name(),
            'vencimiento_garantia' => $this->faker->date(),
        ];
    }
} 