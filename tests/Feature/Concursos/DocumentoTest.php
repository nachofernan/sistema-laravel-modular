<?php

namespace Tests\Feature\Concursos;

use App\Models\Concursos\Documento;
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
        ], 'concursos');
    }

    /** @test */
    public function documento_tiene_relacion_con_concurso()
    {
        // Este test verifica la relación explícita con concurso
        $documento = Documento::factory()->create();
        $this->assertNotNull($documento->concurso);
    }
} 