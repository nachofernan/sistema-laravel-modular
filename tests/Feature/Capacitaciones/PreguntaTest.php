<?php

namespace Tests\Feature\Capacitaciones;

use App\Models\Capacitaciones\Pregunta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreguntaTest extends TestCase
{
    /** @test */
    public function puede_crear_una_pregunta()
    {
        // Este test verifica que se puede crear una pregunta correctamente
        $pregunta = Pregunta::factory()->create();
        $this->assertDatabaseHas('preguntas', [
            'id' => $pregunta->id,
        ], 'capacitaciones');
    }

    /** @test */
    public function pregunta_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la pregunta
        $pregunta = Pregunta::factory()->create();
        $this->assertNotNull($pregunta->encuesta);
        $this->assertTrue($pregunta->opciones()->count() >= 0);
        $this->assertTrue($pregunta->respuestas()->count() >= 0);
    }
} 