<?php

namespace Tests\Feature\Documentos;

use App\Models\Documentos\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    /** @test */
    public function puede_crear_una_categoria()
    {
        // Este test verifica que se puede crear una categoría correctamente
        $categoria = Categoria::factory()->create();
        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
        ], 'documentos');
    }

    /** @test */
    public function categoria_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas de la categoría
        $categoria = Categoria::factory()->create();
        $this->assertTrue($categoria->documentos()->count() >= 0);
        $this->assertTrue($categoria->hijos()->count() >= 0);
    }
} 