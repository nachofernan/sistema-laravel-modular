<?php

namespace Tests\Feature\Capacitaciones;

use App\Models\Capacitaciones\Documento;
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
        ], 'capacitaciones');
    }

    /** @test */
    public function documento_tiene_relacion_con_capacitacion()
    {
        // Este test verifica la relación explícita con capacitación
        $documento = Documento::factory()->create();
        $this->assertNotNull($documento->capacitacion);
    }
} 