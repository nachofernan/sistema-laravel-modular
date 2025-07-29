<?php

namespace Tests\Feature\Usuarios;

use App\Models\Usuarios\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    /** @test */
    public function puede_registrar_un_evento_de_usuario()
    {
        // Este test verifica que se puede registrar un evento en el log de usuario
        $usuario = User::factory()->create();
        $log = Log::create([
            'user_id' => $usuario->id,
            'ip_address' => '127.0.0.1',
            'event' => 'login',
        ]);
        $this->assertDatabaseHas('logs', [
            'id' => $log->id,
            'ip_address' => '127.0.0.1',
            'event' => 'login',
        ], 'usuarios');
    }

    /** @test */
    public function traduce_evento_a_nombre_amigable()
    {
        // Este test verifica que el evento se traduce a un nombre amigable en español
        $nombre = Log::getFriendlyEventName('password_change');
        $this->assertEquals('Cambio de contraseña', $nombre);
    }
} 