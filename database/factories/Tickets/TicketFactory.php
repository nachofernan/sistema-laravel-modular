<?php

namespace Database\Factories\Tickets;

use App\Models\Tickets\Ticket;
use App\Models\Tickets\Estado;
use App\Models\Tickets\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'estado_id' => Estado::factory(),
            'categoria_id' => Categoria::factory(),
            'user_id' => User::factory(),
            'user_encargado_id' => User::factory(),
            // Agrega otros campos requeridos si existen
        ];
    }
} 