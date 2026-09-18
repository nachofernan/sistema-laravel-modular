<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Proveedor;
use App\Models\User;
use App\Models\Usuarios\Permission;
use App\Models\Usuarios\Role;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ProveedorTest extends TestCase
{
    private function usuarioQueEditaProveedores(): User
    {
        $rol = Role::firstOrCreate(['name' => 'Proveedores/Acceso', 'guard_name' => 'web']);
        $permiso = Permission::firstOrCreate(['name' => 'Proveedores/Proveedores/Editar', 'guard_name' => 'web']);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user = User::factory()->create();
        $user->assignRole($rol);
        $user->givePermissionTo($permiso);

        return $user;
    }

    #[Test]
    public function puede_crear_un_proveedor()
    {
        // Este test verifica que se puede crear un proveedor correctamente
        $proveedor = Proveedor::factory()->create();
        $this->assertDatabaseHas('proveedors', [
            'id' => $proveedor->id,
        ], 'proveedores');
    }

    #[Test]
    public function puede_crear_un_proveedor_con_cuit_alfanumerico()
    {
        // Smoke test de la migración que cambió cuit de bigint a varchar(30)
        $proveedor = Proveedor::factory()->create(['cuit' => 'RUT12345CL']);
        $this->assertDatabaseHas('proveedors', [
            'id' => $proveedor->id,
            'cuit' => 'RUT12345CL',
        ], 'proveedores');
    }

    #[Test]
    public function alta_de_proveedor_extranjero_sanitiza_cuit_y_lo_normaliza_a_mayusculas()
    {
        $this->actingAs($this->usuarioQueEditaProveedores());

        $this->post(route('proveedores.proveedors.store'), [
            'cuit' => 'ab-12.34/56 cd',
            'razonsocial' => 'Proveedor Extranjero SA',
            'correo' => 'extranjero@example.com',
        ]);

        $this->assertDatabaseHas('proveedors', [
            'cuit' => 'AB123456CD',
        ], 'proveedores');
    }

    #[Test]
    public function alta_de_proveedor_con_cuit_muy_corto_falla_validacion()
    {
        $this->actingAs($this->usuarioQueEditaProveedores());

        $response = $this->post(route('proveedores.proveedors.store'), [
            'cuit' => 'AB12',
            'razonsocial' => 'Proveedor Corto SA',
            'correo' => 'corto@example.com',
        ]);

        $response->assertSessionHasErrors('cuit');
        $this->assertDatabaseMissing('proveedors', ['razonsocial' => 'Proveedor Corto SA'], 'proveedores');
    }

    #[Test]
    public function alta_de_proveedor_con_cuit_muy_largo_falla_validacion()
    {
        $this->actingAs($this->usuarioQueEditaProveedores());

        $response = $this->post(route('proveedores.proveedors.store'), [
            'cuit' => str_repeat('A', 21),
            'razonsocial' => 'Proveedor Largo SA',
            'correo' => 'largo@example.com',
        ]);

        $response->assertSessionHasErrors('cuit');
        $this->assertDatabaseMissing('proveedors', ['razonsocial' => 'Proveedor Largo SA'], 'proveedores');
    }

    #[Test]
    public function proveedor_tiene_relaciones_basicas()
    {
        // Este test verifica las relaciones explícitas del proveedor
        $proveedor = Proveedor::factory()->create();
        $this->assertNotNull($proveedor->estado);
        $this->assertNotNull($proveedor->creador);
    }
}
