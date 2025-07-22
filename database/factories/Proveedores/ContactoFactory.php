<?php

namespace Database\Factories\Proveedores;

use App\Models\Proveedores\Contacto;
use App\Models\Proveedores\Proveedor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactoFactory extends Factory
{
    protected $model = Contacto::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'telefono' => $this->faker->phoneNumber(),
            'correo' => $this->faker->safeEmail(),
            'proveedor_id' => Proveedor::factory(),
            'user_id_created' => User::factory(),
            'user_id_updated' => User::factory(),
        ];
    }
} 