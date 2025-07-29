<?php

namespace Tests\Feature\Usuarios;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    /** @test */
    public function puede_registrar_un_usuario()
    {
        // Este test verifica que se puede registrar un usuario correctamente
        $usuario = User::factory()->create();
        $this->assertDatabaseHas('users', [
            'email' => $usuario->email,
        ], 'usuarios'); // Cambia 'usuarios' si usas otra conexión
    }

    /** @test */
    public function puede_actualizar_el_perfil_de_un_usuario()
    {
        // Este test verifica que se puede actualizar el perfil de un usuario
        $usuario = User::factory()->create();
        $usuario->name = 'Nombre Actualizado';
        $usuario->save();
        $this->assertDatabaseHas('users', [
            'id' => $usuario->id,
            'name' => 'Nombre Actualizado',
        ], 'usuarios');
    }
} 