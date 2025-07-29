<?php

namespace Tests\Feature\Usuarios;

use App\Models\Usuarios\PasswordSecurity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordSecurityTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    /** @test */
    public function puede_asociar_seguridad_de_contrasena_a_usuario()
    {
        // Este test verifica que se puede asociar un registro de seguridad de contraseña a un usuario
        $usuario = User::factory()->create();
        $ps = PasswordSecurity::create([
            'user_id' => $usuario->id,
            'password_expiry_days' => 180,
        ]);
        $this->assertEquals($usuario->id, $ps->user->id);
    }
} 