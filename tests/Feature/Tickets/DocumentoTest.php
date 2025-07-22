<?php

namespace Tests\Feature\Tickets;

use App\Models\Tickets\Documento;
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
        ], 'tickets');
    }

    /** @test */
    public function documento_tiene_relacion_con_ticket()
    {
        // Este test verifica la relación explícita con ticket
        $documento = Documento::factory()->create();
        $this->assertNotNull($documento->ticket);
    }
} 