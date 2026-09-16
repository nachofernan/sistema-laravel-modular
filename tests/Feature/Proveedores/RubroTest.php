<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Rubro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RubroTest extends TestCase
{
    #[Test]
    public function puede_crear_un_rubro()
    {
        // Este test verifica que se puede crear un rubro correctamente
        $rubro = Rubro::factory()->create();
        $this->assertDatabaseHas('rubros', [
            'id' => $rubro->id,
        ], 'proveedores');
    }
} 