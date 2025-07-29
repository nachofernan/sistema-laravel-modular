<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Proveedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProveedorTest extends TestCase
{
    /** @test */
    public function puede_crear_un_proveedor()
    {
        // Este test verifica que se puede crear un proveedor correctamente
        $proveedor = Proveedor::factory()->create();
        $this->assertDatabaseHas('proveedors', [
            'id' => $proveedor->id,
        ], 'proveedores');
    }

    /** @test */
    public function proveedor_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del proveedor
        $proveedor = Proveedor::factory()->create();
        $this->assertNotNull($proveedor->estado);
        $this->assertNotNull($proveedor->creador);
    }
} 