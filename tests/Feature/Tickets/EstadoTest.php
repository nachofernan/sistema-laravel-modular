<?php

namespace Tests\Feature\Tickets;

use App\Models\Tickets\Estado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstadoTest extends TestCase
{
    /** @test */
    public function puede_crear_un_estado()
    {
        // Este test verifica que se puede crear un estado correctamente
        $estado = Estado::factory()->create();
        $this->assertDatabaseHas('estados', [
            'id' => $estado->id,
        ], 'tickets');
    }
} 