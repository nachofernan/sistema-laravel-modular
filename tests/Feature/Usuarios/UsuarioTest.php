<?php

use App\Models\User;
use App\Models\Usuarios\Cargo;

test('puede crear un usuario', function () {
    $user = User::factory()->create();

    expect($user->id)->not->toBeNull()
        ->and($user->email)->toBeString()->not->toBeEmpty()
        ->and($user->legajo)->not->toBeNull();
});

test('un usuario sin roles no es super admin', function () {
    $user = User::factory()->create();

    expect($user->isSuperAdmin())->toBeFalse();
});

test('un usuario puede tener un cargo', function () {
    $cargo = Cargo::factory()->create(['nombre' => 'Analista']);
    $user = User::factory()->create(['cargo_id' => $cargo->id]);

    expect($user->cargo->nombre)->toBe('Analista');
});

test('nombreCompleto usa realname cuando está cargado', function () {
    $user = User::factory()->create(['realname' => 'Juan Pérez']);

    expect($user->nombreCompleto)->toBe('Juan Pérez');
});

test('nombreCompleto cae a apellido y nombre si no hay realname', function () {
    $user = User::factory()->create([
        'realname' => '',
        'nombre' => 'María',
        'apellido' => 'Gómez',
    ]);

    expect($user->nombreCompleto)->toBe('Gómez, María');
});

test('nombreCompleto cae al name como último recurso', function () {
    $user = User::factory()->create([
        'realname' => '',
        'nombre' => null,
        'apellido' => null,
        'name' => 'jusername',
    ]);

    expect($user->nombreCompleto)->toBe('jusername');
});
