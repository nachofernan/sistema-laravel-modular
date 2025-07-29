<?php

namespace Tests\Feature\Inventario;

use App\Models\Inventario\Entrega;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntregaTest extends TestCase
{
    /** @test */
    public function puede_crear_una_entrega()
    {
        // Este test verifica que se puede crear una entrega correctamente
        $entrega = Entrega::factory()->create();
        $this->assertDatabaseHas('entregas', [
            'id' => $entrega->id,
        ], 'inventario');
    }

    /** @test */
    public function entrega_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la entrega
        $entrega = Entrega::factory()->create();
        $this->assertNotNull($entrega->elemento);
        $this->assertNotNull($entrega->user);
    }
} 