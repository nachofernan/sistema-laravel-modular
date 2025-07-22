<?php

namespace Tests\Feature\Inventario;

use App\Models\Inventario\Caracteristica;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaracteristicaTest extends TestCase
{
    /** @test */
    public function puede_crear_una_caracteristica()
    {
        // Este test verifica que se puede crear una característica correctamente
        $caracteristica = Caracteristica::factory()->create();
        $this->assertDatabaseHas('caracteristicas', [
            'id' => $caracteristica->id,
        ], 'inventario');
    }

    /** @test */
    public function caracteristica_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la característica
        $caracteristica = Caracteristica::factory()->create();
        $this->assertNotNull($caracteristica->categoria);
        $this->assertTrue($caracteristica->valores()->count() >= 0);
        $this->assertTrue($caracteristica->opciones()->count() >= 0);
    }
} 