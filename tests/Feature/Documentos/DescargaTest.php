<?php

namespace Tests\Feature\Documentos;

use App\Models\Documentos\Descarga;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DescargaTest extends TestCase
{
    #[Test]
    public function puede_crear_una_descarga()
    {
        // Este test verifica que se puede crear una descarga correctamente
        $descarga = Descarga::factory()->create();
        $this->assertDatabaseHas('descargas', [
            'id' => $descarga->id,
        ], 'documentos');
    }
}
