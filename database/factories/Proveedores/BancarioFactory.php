<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Bancario;
use App\Models\Proveedores\Proveedor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BancarioFactory extends Factory
{
    protected $model = Bancario::class;

    public function definition()
    {
        return [
            'cbu' => $this->faker->optional()->numerify('###############'),
            'alias' => $this->faker->optional()->word(),
            'tipocuenta' => $this->faker->optional()->word(),
            'numerocuenta' => $this->faker->optional()->numerify('########'),
            'banco' => $this->faker->optional()->company(),
            'sucursal' => $this->faker->optional()->word(),
            'proveedor_id' => Proveedor::factory(),
            'user_id_created' => User::factory(),
            'user_id_updated' => User::factory(),
        ];
    }
} 