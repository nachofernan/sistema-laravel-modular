<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Apoderado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApoderadoTest extends TestCase
{
    /** @test */
    public function puede_crear_un_apoderado()
    {
        // Este test verifica que se puede crear un apoderado correctamente
        $apoderado = Apoderado::factory()->create();
        $this->assertDatabaseHas('apoderados', [
            'id' => $apoderado->id,
        ], 'proveedores');
    }

    /** @test */
    public function apoderado_tiene_relacion_con_proveedor()
    {
        // Este test verifica la relación explícita con proveedor
        $apoderado = Apoderado::factory()->create();
        $this->assertNotNull($apoderado->proveedor);
    }
} 