<?php

namespace Tests\Feature\Usuarios;

use App\Models\User;
use App\Models\Usuarios\Role;
use App\Models\Usuarios\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolPermisoTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    /** @test */
    public function puede_asignar_rol_a_usuario()
    {
        // Este test verifica que se puede asignar un rol a un usuario
        $usuario = User::factory()->create();
        $rol = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $usuario->assignRole($rol);
        $this->assertTrue($usuario->hasRole('admin'));
    }

    /** @test */
    public function puede_asignar_permiso_a_rol()
    {
        // Este test verifica que se puede asignar un permiso a un rol
        $permiso = Permission::create(['name' => 'ver usuarios', 'guard_name' => 'web']);
        $rol = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $rol->givePermissionTo($permiso);
        $this->assertTrue($rol->hasPermissionTo('ver usuarios'));
    }

    protected function tearDown(): void
    {
        // Elimina los roles y permisos usados en los tests
        Role::where('name', 'admin')->where('guard_name', 'web')->delete();
        Role::where('name', 'editor')->where('guard_name', 'web')->delete();
        Permission::where('name', 'ver usuarios')->where('guard_name', 'web')->delete();

        parent::tearDown();
    }
} 