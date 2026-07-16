<?php

use App\Models\User;
use App\Models\Usuarios\Cargo;

test('puede crear un cargo', function () {
    $cargo = Cargo::factory()->create(['nombre' => 'Gerente']);

    expect($cargo->id)->not->toBeNull()
        ->and($cargo->nombre)->toBe('Gerente');
});

test('un usuario puede tener un cargo', function () {
    $cargo = Cargo::factory()->create();
    $user = User::factory()->create(['cargo_id' => $cargo->id]);

    expect($user->cargo->id)->toBe($cargo->id)
        ->and($cargo->users->pluck('id')->all())->toContain($user->id);
});
