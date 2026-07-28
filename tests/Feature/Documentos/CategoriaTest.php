<?php

use App\Livewire\Documentos\Categorias\Show\Edit;
use App\Models\Documentos\Categoria;
use App\Models\User;
use App\Models\Usuarios\Permission;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;

/**
 * Usuario con el permiso que exige el modal de edición de categorías. El permiso ya
 * existe en la base de dev; firstOrCreate lo reutiliza si está.
 */
function usuarioQueEditaCategorias(): User
{
    $permiso = Permission::firstOrCreate(['name' => 'Documentos/Categorias/Editar', 'guard_name' => 'web']);
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $user = User::factory()->create();
    $user->givePermissionTo($permiso);

    return $user;
}

test('puede crear una categoría de documentos', function () {
    $categoria = Categoria::factory()->create();

    expect($categoria->id)->not->toBeNull()
        ->and($categoria->nombre)->toBeString()->not->toBeEmpty();
});

test('el modal de edicion actualiza nombre, orden y visibilidad publica', function () {
    $this->actingAs(usuarioQueEditaCategorias());
    $categoria = Categoria::factory()->create(['nombre' => 'Politicas ZZQ', 'orden' => 4]);

    Livewire::test(Edit::class, ['categoria' => $categoria])
        ->assertSet('nombre', 'Politicas ZZQ')
        ->assertSet('orden', 4)
        ->set('nombre', 'Politicas y Procedimientos ZZQ')
        ->set('orden', 2)
        ->set('publica', false)
        ->call('actualizar')
        ->assertRedirect(route('documentos.categorias.index', absolute: false));

    $categoria = $categoria->fresh();

    expect($categoria->nombre)->toBe('Politicas y Procedimientos ZZQ')
        ->and($categoria->orden)->toBe(2)
        ->and($categoria->publica)->toBeFalse();
});

test('un usuario sin permiso no puede editar una categoria', function () {
    $this->actingAs(User::factory()->create());
    $categoria = Categoria::factory()->create(['nombre' => 'Politicas ZZQ']);

    Livewire::test(Edit::class, ['categoria' => $categoria])
        ->set('nombre', 'Renombrada sin permiso')
        ->call('actualizar')
        ->assertForbidden();

    expect($categoria->fresh()->nombre)->toBe('Politicas ZZQ');
});
