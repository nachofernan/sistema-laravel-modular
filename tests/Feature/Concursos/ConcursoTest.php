<?php

namespace Tests\Feature\Concursos;

use App\Models\Concursos\Concurso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConcursoTest extends TestCase
{
    #[Test]
    public function puede_crear_un_concurso()
    {
        // Este test verifica que se puede crear un concurso correctamente
        $concurso = Concurso::factory()->create();
        $this->assertDatabaseHas('concursos', [
            'id' => $concurso->id,
        ], 'concursos');
    }

    #[Test]
    public function concurso_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del concurso
        $concurso = Concurso::factory()->create();
        $this->assertNotNull($concurso->estado);
        $this->assertNotNull($concurso->usuario);
    }
} 