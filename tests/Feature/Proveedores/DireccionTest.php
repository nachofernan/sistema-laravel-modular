<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Direccion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DireccionTest extends TestCase
{
    #[Test]
    public function puede_crear_una_direccion()
    {
        // Este test verifica que se puede crear una dirección correctamente
        $direccion = Direccion::factory()->create();
        $this->assertDatabaseHas('direccions', [
            'id' => $direccion->id,
        ], 'proveedores');
    }

    #[Test]
    public function direccion_tiene_relacion_con_proveedor()
    {
        // Este test verifica la relación explícita con proveedor
        $direccion = Direccion::factory()->create();
        $this->assertNotNull($direccion->proveedor);
    }
} 