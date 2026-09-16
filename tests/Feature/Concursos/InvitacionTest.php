<?php

namespace Tests\Feature\Concursos;

use App\Models\Concursos\Invitacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvitacionTest extends TestCase
{
    #[Test]
    public function puede_crear_una_invitacion()
    {
        // Este test verifica que se puede crear una invitación correctamente
        $invitacion = Invitacion::factory()->create();
        $this->assertDatabaseHas('invitacions', [
            'id' => $invitacion->id,
        ], 'concursos');
    }

    #[Test]
    public function invitacion_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la invitación
        $invitacion = Invitacion::factory()->create();
        $this->assertNotNull($invitacion->concurso);
        $this->assertNotNull($invitacion->proveedor);
    }
} 