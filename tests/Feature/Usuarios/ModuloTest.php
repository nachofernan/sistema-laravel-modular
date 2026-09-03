<?php

namespace Tests\Feature\Usuarios;

use App\Models\Usuarios\Modulo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModuloTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    #[Test]
    public function puede_obtener_estados_de_modulo()
    {
        // Este test verifica que se pueden obtener los estados posibles de un módulo
        $estados = Modulo::getEstados();
        $this->assertIsArray($estados);
    }
} 