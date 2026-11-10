<?php

namespace Tests\Feature\Usuarios;

use App\Livewire\Usuarios\Users\Index\ResetPasswordModal;
use App\Models\User;
use App\Models\Usuarios\Log;
use App\Models\Usuarios\PasswordSecurity;
use App\Models\Usuarios\Permission;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    #[Test]
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

    #[Test]
    public function traduce_evento_a_nombre_amigable()
    {
        // Este test verifica que el evento se traduce a un nombre amigable en español
        $nombre = Log::getFriendlyEventName('password_change');
        $this->assertEquals('Cambio de contraseña', $nombre);
    }

    #[Test]
    public function el_login_registra_un_acceso()
    {
        $usuario = User::factory()->create();

        Auth::login($usuario);

        $this->assertDatabaseHas('logs', ['user_id' => $usuario->id, 'event' => 'login'], 'usuarios');
    }

    #[Test]
    public function el_logout_registra_un_acceso()
    {
        $usuario = User::factory()->create();
        Auth::login($usuario);

        Auth::logout();

        $this->assertDatabaseHas('logs', ['user_id' => $usuario->id, 'event' => 'logout'], 'usuarios');
    }

    #[Test]
    public function un_login_fallido_se_registra()
    {
        $usuario = User::factory()->create();

        Auth::attempt(['email' => $usuario->email, 'password' => 'incorrecta']);

        $this->assertDatabaseHas('logs', ['user_id' => $usuario->id, 'event' => 'login_fallido'], 'usuarios');
        $this->assertDatabaseMissing('logs', ['user_id' => $usuario->id, 'event' => 'login'], 'usuarios');
    }

    #[Test]
    public function el_reset_por_mail_registra_un_cambio_de_contrasena()
    {
        $usuario = User::factory()->create();

        event(new PasswordReset($usuario));

        $this->assertDatabaseHas('logs', ['user_id' => $usuario->id, 'event' => 'password_change'], 'usuarios');
    }

    #[Test]
    public function el_reset_por_admin_se_registra()
    {
        $admin = $this->usuarioConPermiso('Usuarios/Usuarios/Editar');
        $usuario = User::factory()->create();
        PasswordSecurity::create([
            'user_id' => $usuario->id,
            'password_expiry_days' => 180,
            'password_updated_at' => now(),
        ]);

        Livewire::actingAs($admin)
            ->test(ResetPasswordModal::class, ['user' => $usuario])
            ->set('nueva_password', 'NuevaClave123')
            ->call('reset_password')
            ->assertSet('pass_set', true);

        $this->assertDatabaseHas('logs', ['user_id' => $usuario->id, 'event' => 'password_reset_admin'], 'usuarios');
        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $usuario->id,
            'event' => 'password_reset',
            'causer_id' => $admin->id,
        ], 'mysql');
    }

    #[Test]
    public function un_usuario_sin_permiso_no_puede_resetear_contrasenas()
    {
        $usuario = User::factory()->create();
        $hashOriginal = $usuario->password;

        Livewire::actingAs(User::factory()->create())
            ->test(ResetPasswordModal::class, ['user' => $usuario])
            ->set('nueva_password', 'NuevaClave123')
            ->call('reset_password')
            ->assertForbidden();

        $this->assertSame($hashOriginal, $usuario->fresh()->password);
    }

    private function usuarioConPermiso(string $permiso): User
    {
        Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        $usuario = User::factory()->create();
        $usuario->givePermissionTo($permiso);

        return $usuario;
    }
}
