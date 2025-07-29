<?php

namespace Tests\Feature\Capacitaciones;

use App\Models\Capacitaciones\Invitacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitacionTest extends TestCase
{
    /** @test */
    public function puede_crear_una_invitacion()
    {
        // Este test verifica que se puede crear una invitación correctamente
        $invitacion = Invitacion::factory()->create();
        $this->assertDatabaseHas('invitacions', [
            'id' => $invitacion->id,
        ], 'capacitaciones');
    }

    /** @test */
    public function invitacion_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la invitación
        $invitacion = Invitacion::factory()->create();
        $this->assertNotNull($invitacion->capacitacion);
        $this->assertNotNull($invitacion->usuario);
    }
} 