<?php

use App\Models\User;

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
