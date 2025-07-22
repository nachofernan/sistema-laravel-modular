<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Subrubro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubrubroTest extends TestCase
{
    /** @test */
    public function puede_crear_un_subrubro()
    {
        // Este test verifica que se puede crear un subrubro correctamente
        $subrubro = Subrubro::factory()->create();
        $this->assertDatabaseHas('subrubros', [
            'id' => $subrubro->id,
        ], 'proveedores');
    }

    /** @test */
    public function subrubro_tiene_relacion_con_rubro()
    {
        // Este test verifica la relación explícita con rubro
        $subrubro = Subrubro::factory()->create();
        $this->assertNotNull($subrubro->rubro);
    }
} 