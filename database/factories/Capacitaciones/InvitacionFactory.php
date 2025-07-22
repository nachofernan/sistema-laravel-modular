<?php

namespace Database\Factories\Capacitaciones;

use App\Models\Capacitaciones\Invitacion;
use App\Models\Capacitaciones\Capacitacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvitacionFactory extends Factory
{
    protected $model = Invitacion::class;

    public function definition()
    {
        return [
            'capacitacion_id' => Capacitacion::factory(),
            'user_id' => User::factory(),
        ];
    }
} 