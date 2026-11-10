<?php

use App\Models\User;
use App\Models\Usuarios\Role;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

/**
 * Historial de cambios de usuarios (spatie/activitylog sobre la base usuarios).
 */
function historialDe(User $usuario)
{
    return Activity::where('subject_type', User::class)
        ->where('subject_id', $usuario->id)
        ->orderBy('id')
        ->get();
}

function rolDePrueba(): Role
{
    return Role::create(['name' => 'Test/'.Str::random(8), 'guard_name' => 'web']);
}

test('crear_un_usuario_registra_quien_lo_creo', function () {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $usuario = User::factory()->create();

    $actividad = historialDe($usuario)->first();
    expect($actividad->event)->toBe('created')
        ->and($actividad->description)->toBe('creado')
        ->and($actividad->causer_id)->toBe($admin->id)
        ->and($actividad->properties['attributes']['email'])->toBe($usuario->email);
});

test('editar_un_usuario_registra_valor_anterior_y_nuevo', function () {
    $usuario = User::factory()->create(['nombre' => 'Juan']);
    $this->actingAs(User::factory()->create());

    $usuario->update(['nombre' => 'Pedro']);

    $actividad = historialDe($usuario)->last();
    expect($actividad->event)->toBe('updated')
        ->and($actividad->properties['old'])->toBe(['nombre' => 'Juan'])
        ->and($actividad->properties['attributes'])->toBe(['nombre' => 'Pedro']);
});

test('editar_la_contrasena_no_guarda_la_contrasena_en_el_historial', function () {
    $usuario = User::factory()->create();
    $cantidadAntes = historialDe($usuario)->count();

    $usuario->update(['password' => bcrypt('OtraClave123')]);

    $historial = historialDe($usuario);
    expect($historial)->toHaveCount($cantidadAntes)
        ->and($historial->pluck('properties')->toJson())->not->toContain('password');
});

test('sincronizar_roles_registra_agregados_y_quitados', function () {
    $rolViejo = rolDePrueba();
    $rolNuevo = rolDePrueba();
    $usuario = User::factory()->create();
    $usuario->syncRoles([$rolViejo->name]);

    $usuario->sincronizarRoles([$rolNuevo->name]);

    $actividad = historialDe($usuario)->last();
    expect($actividad->event)->toBe('roles')
        ->and($actividad->properties['agregados'])->toBe([$rolNuevo->name])
        ->and($actividad->properties['quitados'])->toBe([$rolViejo->name])
        ->and($usuario->fresh()->hasRole($rolNuevo->name))->toBeTrue();
});

test('sincronizar_los_mismos_roles_no_registra_nada', function () {
    $rol = rolDePrueba();
    $usuario = User::factory()->create();
    $usuario->syncRoles([$rol->name]);
    $cantidadAntes = historialDe($usuario)->count();

    $usuario->sincronizarRoles([$rol->name]);

    expect(historialDe($usuario))->toHaveCount($cantidadAntes);
});

test('la_actividad_se_guarda_en_la_conexion_usuarios', function () {
    expect((new Activity)->getConnectionName())->toBe('usuarios');

    $usuario = User::factory()->create();

    $this->assertDatabaseHas('activity_log', [
        'subject_type' => User::class,
        'subject_id' => $usuario->id,
    ], 'usuarios');
});
