<?php

namespace Tests\Feature\Inventario;

use App\Models\Inventario\Valor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ValorTest extends TestCase
{
    #[Test]
    public function puede_crear_un_valor()
    {
        // Este test verifica que se puede crear un valor correctamente
        $valor = Valor::factory()->create();
        $this->assertDatabaseHas('valors', [
            'id' => $valor->id,
        ], 'inventario');
    }

    #[Test]
    public function valor_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del valor
        $valor = Valor::factory()->create();
        $this->assertNotNull($valor->elemento);
        $this->assertNotNull($valor->caracteristica);
    }
} 