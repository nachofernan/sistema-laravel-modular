<?php

namespace Tests\Feature\Inventario;

use App\Models\Inventario\Periferico;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerifericoTest extends TestCase
{
    /** @test */
    public function puede_crear_un_periferico()
    {
        // Este test verifica que se puede crear un periférico correctamente
        $periferico = Periferico::factory()->create();
        $this->assertDatabaseHas('perifericos', [
            'id' => $periferico->id,
        ], 'inventario');
    }
} 