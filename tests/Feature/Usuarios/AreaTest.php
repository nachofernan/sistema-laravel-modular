<?php

namespace Tests\Feature\Usuarios;

use App\Models\Usuarios\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AreaTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    /** @test */
    public function puede_crear_un_area()
    {
        // Este test verifica que se puede crear un área correctamente
        $area = Area::factory()->create();
        $this->assertDatabaseHas('areas', [
            'id' => $area->id,
        ], 'usuarios');
    }

    /** @test */
    public function puede_asignar_area_padre()
    {
        // Este test verifica la relación padre/hijo entre áreas
        $padre = Area::factory()->create();
        $hijo = Area::factory()->create(['area_id' => $padre->id]);
        $this->assertEquals($padre->id, $hijo->padre->id);
    }
} 