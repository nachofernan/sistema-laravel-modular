<?php

namespace Tests\Feature\Capacitaciones;

use App\Models\Capacitaciones\Respuesta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RespuestaTest extends TestCase
{
    #[Test]
    public function puede_crear_una_respuesta()
    {
        // Este test verifica que se puede crear una respuesta correctamente
        $respuesta = Respuesta::factory()->create();
        $this->assertDatabaseHas('respuestas', [
            'id' => $respuesta->id,
        ], 'capacitaciones');
    }

    #[Test]
    public function respuesta_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la respuesta
        $respuesta = Respuesta::factory()->create();
        $this->assertNotNull($respuesta->pregunta);
        $this->assertNotNull($respuesta->opcion);
        $this->assertNotNull($respuesta->usuario);
    }
} 