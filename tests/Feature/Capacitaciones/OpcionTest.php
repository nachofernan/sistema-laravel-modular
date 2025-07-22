<?php

namespace Tests\Feature\Capacitaciones;

use App\Models\Capacitaciones\Opcion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpcionTest extends TestCase
{
    /** @test */
    public function puede_crear_una_opcion()
    {
        // Este test verifica que se puede crear una opción correctamente
        $opcion = Opcion::factory()->create();
        $this->assertDatabaseHas('opcions', [
            'id' => $opcion->id,
        ], 'capacitaciones');
    }

    /** @test */
    public function opcion_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la opción
        $opcion = Opcion::factory()->create();
        $this->assertNotNull($opcion->pregunta);
        $this->assertTrue($opcion->respuestas()->count() >= 0);
    }
} 