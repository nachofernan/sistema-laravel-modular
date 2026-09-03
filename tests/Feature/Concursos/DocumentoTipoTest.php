<?php

namespace Tests\Feature\Concursos;

use App\Models\Concursos\DocumentoTipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DocumentoTipoTest extends TestCase
{
    #[Test]
    public function puede_crear_un_documento_tipo()
    {
        // Este test verifica que se puede crear un tipo de documento correctamente
        $tipo = DocumentoTipo::factory()->create();
        $this->assertDatabaseHas('documento_tipos', [
            'id' => $tipo->id,
        ], 'concursos');
    }
} 