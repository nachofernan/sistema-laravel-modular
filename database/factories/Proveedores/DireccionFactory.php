<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Direccion;
use App\Models\Proveedores\Proveedor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DireccionFactory extends Factory
{
    protected $model = Direccion::class;

    public function definition()
    {
        return [
            'tipo' => $this->faker->randomElement(['fiscal','real','legal']),
            'calle' => $this->faker->streetName(),
            'altura' => $this->faker->buildingNumber(),
            'piso' => $this->faker->optional()->randomDigit(),
            'departamento' => $this->faker->optional()->bothify('A#'),
            'ciudad' => $this->faker->city(),
            'codigopostal' => $this->faker->postcode(),
            'provincia' => $this->faker->state(),
            'pais' => $this->faker->country(),
            'proveedor_id' => Proveedor::factory(),
            'user_id_created' => User::factory(),
            'user_id_updated' => User::factory(),
        ];
    }
} 