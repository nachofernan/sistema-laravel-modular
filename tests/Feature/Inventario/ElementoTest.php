<?php

namespace Tests\Feature\Inventario;

use App\Models\Inventario\Elemento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElementoTest extends TestCase
{
    /** @test */
    public function puede_crear_un_elemento()
    {
        // Este test verifica que se puede crear un elemento correctamente
        $elemento = Elemento::factory()->create();
        $this->assertDatabaseHas('elementos', [
            'id' => $elemento->id,
        ], 'inventario');
    }

    /** @test */
    public function elemento_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del elemento
        $elemento = Elemento::factory()->create();
        $this->assertNotNull($elemento->categoria);
        $this->assertNotNull($elemento->estado);
        $this->assertNotNull($elemento->user);
        $this->assertNotNull($elemento->area);
        $this->assertNotNull($elemento->sede);
    }
} 