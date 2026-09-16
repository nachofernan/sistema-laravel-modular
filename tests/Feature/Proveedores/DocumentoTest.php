<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Documento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DocumentoTest extends TestCase
{
    #[Test]
    public function puede_crear_un_documento()
    {
        // Este test verifica que se puede crear un documento correctamente
        $documento = Documento::factory()->create();
        $this->assertDatabaseHas('documentos', [
            'id' => $documento->id,
        ], 'proveedores');
    }

    #[Test]
    public function documento_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del documento
        $documento = Documento::factory()->create();
        $this->assertNotNull($documento->documentable);
        if ($documento->documentable_type === 'proveedor') {
            $this->assertNotNull($documento->documento_tipo);
        } else {
            $this->assertNull($documento->documento_tipo);
        }
    }
} 