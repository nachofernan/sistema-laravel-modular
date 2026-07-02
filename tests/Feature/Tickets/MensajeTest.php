<?php

use App\Models\Tickets\Mensaje;

test('puede crear un mensaje', function () {
    $mensaje = Mensaje::factory()->create();

    expect($mensaje->id)->not->toBeNull()
        ->and($mensaje->mensaje)->toBeString()->not->toBeEmpty();
});

test('un mensaje pertenece a un ticket y a un usuario', function () {
    $mensaje = Mensaje::factory()->create();

    expect($mensaje->ticket)->not->toBeNull()
        ->and($mensaje->user)->not->toBeNull();
});
