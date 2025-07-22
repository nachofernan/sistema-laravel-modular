<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\Invitacion;
use App\Models\Concursos\Concurso;
use App\Models\Proveedores\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitacionFactory extends Factory
{
    protected $model = Invitacion::class;

    public function definition()
    {
        return [
            'concurso_id' => Concurso::factory(),
            'proveedor_id' => Proveedor::factory(),
            'intencion' => $this->faker->numberBetween(0, 3),
            'fecha_envio' => $this->faker->optional()->date(),
        ];
    }
} 