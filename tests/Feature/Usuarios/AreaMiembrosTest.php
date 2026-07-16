<?php

use App\Livewire\Usuarios\Areas\Miembros;
use App\Models\User;
use App\Models\Usuarios\Area;
use App\Models\Usuarios\Permission;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Crea un usuario con el permiso Usuarios/Areas/Editar (el que exige el componente).
 * El permiso ya existe en la base de dev; firstOrCreate lo reutiliza si está.
 */
function usuarioQueEditaAreas(): User
{
    $permiso = Permission::firstOrCreate(['name' => 'Usuarios/Areas/Editar', 'guard_name' => 'web']);
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $user = User::factory()->create();
    $user->givePermissionTo($permiso);

    return $user;
}

test('agregar mueve un usuario a esta área', function () {
    $this->actingAs(usuarioQueEditaAreas());
    $area = Area::factory()->create();
    $otra = Area::factory()->create();
    $user = User::factory()->create(['area_id' => $otra->id]);

    $componente = new Miembros();
    $componente->mount($area);
    $componente->agregar($user->id);

    expect($user->fresh()->area_id)->toBe($area->id);
});

test('quitar saca al usuario del área y limpia el responsable si era él', function () {
    $this->actingAs(usuarioQueEditaAreas());
    $area = Area::factory()->create();
    $user = User::factory()->create(['area_id' => $area->id]);
    $area->update(['responsable_id' => $user->id]);

    $componente = new Miembros();
    $componente->mount($area);
    $componente->quitar($user->id);

    expect($user->fresh()->area_id)->toBeNull()
        ->and($area->fresh()->responsable_id)->toBeNull();
});

test('marcarResponsable solo acepta miembros del área', function () {
    $this->actingAs(usuarioQueEditaAreas());
    $area = Area::factory()->create();
    $miembro = User::factory()->create(['area_id' => $area->id]);
    $ajeno = User::factory()->create(['area_id' => null]);

    $componente = new Miembros();
    $componente->mount($area);

    $componente->marcarResponsable($miembro->id);
    expect($area->fresh()->responsable_id)->toBe($miembro->id);

    // Un no-miembro no debe poder quedar como responsable.
    $componente->marcarResponsable($ajeno->id);
    expect($area->fresh()->responsable_id)->toBe($miembro->id);
});

test('sin permiso no se puede gestionar el personal del área', function () {
    $this->actingAs(User::factory()->create());
    $area = Area::factory()->create();

    $componente = new Miembros();

    expect(fn () => $componente->mount($area))->toThrow(HttpException::class);
});
