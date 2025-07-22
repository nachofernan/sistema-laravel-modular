<?php

namespace Tests\Feature\Usuarios;

use App\Models\Usuarios\Sede;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SedeTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    /** @test */
    public function puede_crear_una_sede()
    {
        // Este test verifica que se puede crear una sede correctamente
        $sede = Sede::factory()->create();
        $this->assertDatabaseHas('sedes', [
            'id' => $sede->id,
        ], 'usuarios');
    }
} 