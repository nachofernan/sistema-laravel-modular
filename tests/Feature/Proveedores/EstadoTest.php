<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Estado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EstadoTest extends TestCase
{
    #[Test]
    public function puede_crear_un_estado()
    {
        // Este test verifica que se puede crear un estado correctamente
        $estado = Estado::factory()->create();
        $this->assertDatabaseHas('estados', [
            'id' => $estado->id,
        ], 'proveedores');
    }
} 