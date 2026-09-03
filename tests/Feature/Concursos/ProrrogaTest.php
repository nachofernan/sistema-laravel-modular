<?php

namespace Tests\Feature\Concursos;

use App\Models\Concursos\Prorroga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProrrogaTest extends TestCase
{
    #[Test]
    public function puede_crear_una_prorroga()
    {
        // Este test verifica que se puede crear una prórroga correctamente
        $prorroga = Prorroga::factory()->create();
        $this->assertDatabaseHas('prorrogas', [
            'id' => $prorroga->id,
        ], 'concursos');
    }

    #[Test]
    public function prorroga_tiene_relacion_con_concurso()
    {
        // Este test verifica la relación explícita con concurso
        $prorroga = Prorroga::factory()->create();
        $this->assertNotNull($prorroga->concurso);
    }
} 