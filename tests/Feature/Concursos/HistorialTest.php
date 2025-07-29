<?php

namespace Tests\Feature\Concursos;

use App\Models\Concursos\Historial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistorialTest extends TestCase
{
    /** @test */
    public function puede_crear_un_historial()
    {
        // Este test verifica que se puede crear un historial correctamente
        $historial = Historial::factory()->create();
        $this->assertDatabaseHas('historials', [
            'id' => $historial->id,
        ], 'concursos');
    }

    /** @test */
    public function historial_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del historial
        $historial = Historial::factory()->create();
        $this->assertNotNull($historial->concurso);
        $this->assertNotNull($historial->estado);
        $this->assertNotNull($historial->usuario);
    }
} 