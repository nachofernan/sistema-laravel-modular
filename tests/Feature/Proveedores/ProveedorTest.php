<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Proveedor;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProveedorTest extends TestCase
{
    #[Test]
    public function puede_crear_un_proveedor()
    {
        // Este test verifica que se puede crear un proveedor correctamente
        $proveedor = Proveedor::factory()->create();
        $this->assertDatabaseHas('proveedors', [
            'id' => $proveedor->id,
        ], 'proveedores');
    }

    #[Test]
    public function puede_crear_un_proveedor_con_cuit_alfanumerico()
    {
        // Smoke test de la migración que cambió cuit de bigint a varchar(30)
        $proveedor = Proveedor::factory()->create(['cuit' => 'RUT12345CL']);
        $this->assertDatabaseHas('proveedors', [
            'id' => $proveedor->id,
            'cuit' => 'RUT12345CL',
        ], 'proveedores');
    }

    #[Test]
    public function proveedor_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del proveedor
        $proveedor = Proveedor::factory()->create();
        $this->assertNotNull($proveedor->estado);
        $this->assertNotNull($proveedor->creador);
    }
}
