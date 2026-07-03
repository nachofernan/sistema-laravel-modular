<?php

namespace Database\Factories\Automotores;

use App\Models\Automotores\Copres;
use App\Models\Automotores\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CopresFactory extends Factory
{
    protected $model = Copres::class;

    public function definition(): array
    {
        return [
            'fecha' => $this->faker->date(),
            'numero_ticket_factura' => $this->faker->numerify('######'),
            'cuit' => $this->faker->numerify('##-########-#'),
            'vehiculo_id' => Vehiculo::factory(),
            'litros' => $this->faker->randomFloat(2, 5, 80),
            'precio_x_litro' => $this->faker->randomFloat(2, 500, 1200),
            'importe_final' => $this->faker->randomFloat(2, 5000, 80000),
            'es_original' => true,
            'km_vehiculo' => $this->faker->numberBetween(0, 150000),
            'kz' => $this->faker->numberBetween(100000, 999999),
            'salida' => $this->faker->date(),
            'reentrada' => $this->faker->date(),
            'user_id_creator' => null,
        ];
    }
}
