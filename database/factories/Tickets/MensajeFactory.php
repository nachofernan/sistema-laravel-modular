<?php

namespace Database\Factories\Tickets;

use App\Models\Tickets\Mensaje;
use App\Models\Tickets\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MensajeFactory extends Factory
{
    protected $model = Mensaje::class;

    public function definition()
    {
        return [
            'ticket_id' => Ticket::factory(),
            'user_id' => User::factory(),
            'leido' => $this->faker->boolean(),
            'mensaje' => $this->faker->sentence(),
        ];
    }
} 