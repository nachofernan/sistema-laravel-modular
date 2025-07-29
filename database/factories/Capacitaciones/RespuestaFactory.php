<?php

namespace Database\Factories\Capacitaciones;

use App\Models\Capacitaciones\Respuesta;
use App\Models\Capacitaciones\Pregunta;
use App\Models\Capacitaciones\Opcion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RespuestaFactory extends Factory
{
    protected $model = Respuesta::class;

    public function definition()
    {
        return [
            'pregunta_id' => Pregunta::factory(),
            'opcion_id' => Opcion::factory(),
            'user_id' => User::factory(),
        ];
    }
} 