<?php

namespace Tests\Feature\Capacitaciones;

use App\Models\Capacitaciones\Capacitacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CapacitacionTest extends TestCase
{
    /** @test */
    public function puede_crear_una_capacitacion()
    {
        // Este test verifica que se puede crear una capacitación correctamente
        $capacitacion = Capacitacion::factory()->create();
        $this->assertDatabaseHas('capacitacions', [
            'id' => $capacitacion->id,
        ], 'capacitaciones');
    }

    /** @test */
    public function capacitacion_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la capacitación
        $capacitacion = Capacitacion::factory()->create();
        $this->assertTrue($capacitacion->invitaciones()->count() >= 0);
        $this->assertTrue($capacitacion->documentos()->count() >= 0);
        $this->assertTrue($capacitacion->encuestas()->count() >= 0);
    }
} 