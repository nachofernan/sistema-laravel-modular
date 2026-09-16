<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Contacto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactoTest extends TestCase
{
    #[Test]
    public function puede_crear_un_contacto()
    {
        // Este test verifica que se puede crear un contacto correctamente
        $contacto = Contacto::factory()->create();
        $this->assertDatabaseHas('contactos', [
            'id' => $contacto->id,
        ], 'proveedores');
    }

    #[Test]
    public function contacto_tiene_relacion_con_proveedor()
    {
        // Este test verifica la relación explícita con proveedor
        $contacto = Contacto::factory()->create();
        $this->assertNotNull($contacto->proveedor);
    }
} 