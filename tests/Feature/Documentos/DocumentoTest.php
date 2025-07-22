<?php

namespace Tests\Feature\Documentos;

use App\Models\Documentos\Documento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentoTest extends TestCase
{
    /** @test */
    public function puede_crear_un_documento()
    {
        // Este test verifica que se puede crear un documento correctamente
        $documento = Documento::factory()->create();
        $this->assertDatabaseHas('documentos', [
            'id' => $documento->id,
        ], 'documentos');
    }

    /** @test */
    public function documento_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del documento
        $documento = Documento::factory()->create();
        $this->assertNotNull($documento->categoria);
        $this->assertNotNull($documento->user);
        $this->assertNotNull($documento->sede);
    }
} 