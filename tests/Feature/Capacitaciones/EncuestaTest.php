<?php

namespace Tests\Feature\Capacitaciones;

use App\Models\Capacitaciones\Encuesta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EncuestaTest extends TestCase
{
    /** @test */
    public function puede_crear_una_encuesta()
    {
        // Este test verifica que se puede crear una encuesta correctamente
        $encuesta = Encuesta::factory()->create();
        $this->assertDatabaseHas('encuestas', [
            'id' => $encuesta->id,
        ], 'capacitaciones');
    }

    /** @test */
    public function encuesta_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la encuesta
        $encuesta = Encuesta::factory()->create();
        $this->assertNotNull($encuesta->capacitacion);
        $this->assertTrue($encuesta->preguntas()->count() >= 0);
    }
} 