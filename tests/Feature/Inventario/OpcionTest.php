<?php

namespace Tests\Feature\Inventario;

use App\Models\Inventario\Opcion;
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
        ], 'inventario');
    }

    /** @test */
    public function opcion_tiene_relacion_con_caracteristica()
    {
        // Este test verifica la relación explícita con caracteristica
        $opcion = Opcion::factory()->create();
        $this->assertNotNull($opcion->caracteristica);
    }
} 