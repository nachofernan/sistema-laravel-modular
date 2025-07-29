<?php

namespace Tests\Feature\Inventario;

use App\Models\Inventario\Estado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstadoTest extends TestCase
{
    /** @test */
    public function puede_crear_un_estado()
    {
        // Este test verifica que se puede crear un estado correctamente
        $estado = Estado::factory()->create();
        $this->assertDatabaseHas('estados', [
            'id' => $estado->id,
        ], 'inventario');
    }
} 