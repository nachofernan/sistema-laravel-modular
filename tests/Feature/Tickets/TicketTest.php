<?php

namespace Tests\Feature\Tickets;

use App\Models\Tickets\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    /** @test */
    public function puede_crear_un_ticket()
    {
        // Este test verifica que se puede crear un ticket correctamente
        $ticket = Ticket::factory()->create();
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
        ], 'tickets');
    }

    /** @test */
    public function ticket_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del ticket
        $ticket = Ticket::factory()->create();
        $this->assertNotNull($ticket->estado);
        $this->assertNotNull($ticket->categoria);
        $this->assertNotNull($ticket->user);
        $this->assertNotNull($ticket->encargado);
    }
} 