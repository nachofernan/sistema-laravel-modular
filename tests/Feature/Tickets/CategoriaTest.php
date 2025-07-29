<?php

namespace Tests\Feature\Tickets;

use App\Models\Tickets\Categoria;
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
        ], 'tickets');
    }
} 