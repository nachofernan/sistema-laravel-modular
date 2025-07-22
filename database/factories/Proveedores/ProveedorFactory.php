<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Estado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    protected $model = Proveedor::class;

    public function definition()
    {
        return [
            'cuit' => $this->faker->unique()->numberBetween(20000000000, 27999999999),
            'razonsocial' => $this->faker->company(),
            'correo' => $this->faker->unique()->safeEmail(),
            'fantasia' => $this->faker->companySuffix(),
            'telefono' => $this->faker->phoneNumber(),
            'webpage' => $this->faker->url(),
            'horario' => $this->faker->optional()->word(),
            'user_id_created' => User::factory(),
            'user_id_updated' => User::factory(),
            'estado_id' => Estado::factory(),
        ];
    }
} 