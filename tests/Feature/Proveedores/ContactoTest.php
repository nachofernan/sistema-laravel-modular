<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Contacto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactoTest extends TestCase
{
    /** @test */
    public function puede_crear_un_contacto()
    {
        // Este test verifica que se puede crear un contacto correctamente
        $contacto = Contacto::factory()->create();
        $this->assertDatabaseHas('contactos', [
            'id' => $contacto->id,
        ], 'proveedores');
    }

    /** @test */
    public function contacto_tiene_relacion_con_proveedor()
    {
        // Este test verifica la relación explícita con proveedor
        $contacto = Contacto::factory()->create();
        $this->assertNotNull($contacto->proveedor);
    }
} 