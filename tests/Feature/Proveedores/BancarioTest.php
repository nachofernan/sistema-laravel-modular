<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Bancario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BancarioTest extends TestCase
{
    /** @test */
    public function puede_crear_un_bancario()
    {
        // Este test verifica que se puede crear un registro bancario correctamente
        $bancario = Bancario::factory()->create();
        $this->assertDatabaseHas('bancarios', [
            'id' => $bancario->id,
        ], 'proveedores');
    }

    /** @test */
    public function bancario_tiene_relacion_con_proveedor()
    {
        // Este test verifica la relación explícita con proveedor
        $bancario = Bancario::factory()->create();
        $this->assertNotNull($bancario->proveedor);
    }
} 