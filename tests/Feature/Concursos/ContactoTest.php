<?php

namespace Tests\Feature\Concursos;

use App\Models\Concursos\Contacto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactoTest extends TestCase
{
    /** @test */
    public function puede_crear_un_contacto()
    {
        // Este test verifica que se puede crear un contacto correctamente
        $contacto = Contacto::factory()->create();
        $this->assertDatabaseHas('contactos', [
            'id' => $contacto->id,
        ], 'concursos');
    }

    /** @test */
    public function contacto_tiene_relacion_con_concurso()
    {
        // Este test verifica la relación explícita con concurso
        $contacto = Contacto::factory()->create();
        $this->assertNotNull($contacto->concurso);
    }
} 