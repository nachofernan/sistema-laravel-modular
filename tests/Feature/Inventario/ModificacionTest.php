<?php

namespace Tests\Feature\Inventario;

use App\Models\Inventario\Modificacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModificacionTest extends TestCase
{
    /** @test */
    public function puede_crear_una_modificacion()
    {
        // Este test verifica que se puede crear una modificación correctamente
        $modificacion = Modificacion::factory()->create();
        $this->assertDatabaseHas('modificacions', [
            'id' => $modificacion->id,
        ], 'inventario');
    }

    /** @test */
    public function modificacion_tiene_relacion_con_elemento()
    {
        // Este test verifica la relación explícita con elemento
        $modificacion = Modificacion::factory()->create();
        $this->assertNotNull($modificacion->elemento);
    }
} 