<?php

namespace Tests\Feature\Tickets;

use App\Models\Tickets\Mensaje;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MensajeTest extends TestCase
{
    /** @test */
    public function puede_crear_un_mensaje()
    {
        // Este test verifica que se puede crear un mensaje correctamente
        $mensaje = Mensaje::factory()->create();
        $this->assertDatabaseHas('mensajes', [
            'id' => $mensaje->id,
        ], 'tickets');
    }

    /** @test */
    public function mensaje_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del mensaje
        $mensaje = Mensaje::factory()->create();
        $this->assertNotNull($mensaje->ticket);
        $this->assertNotNull($mensaje->user);
    }
} 