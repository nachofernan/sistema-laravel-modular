<?php

use App\Models\Capacitaciones\Invitacion;
use App\Models\Capacitaciones\Capacitacion;
use App\Models\User;

test('puede crear una invitación a una capacitación', function () {
    $invitacion = Invitacion::factory()->create();

    expect($invitacion->id)->not->toBeNull();
});

test('una invitación pertenece a una capacitación y a un usuario', function () {
    $invitacion = Invitacion::factory()->create();

    expect($invitacion->capacitacion)->not->toBeNull()
        ->and($invitacion->capacitacion)->toBeInstanceOf(Capacitacion::class);
});

test('una invitación tiene tipo presencial o virtual', function () {
    $invitacion = Invitacion::factory()->create();

    expect($invitacion->tipo)->toBeIn(['presencial', 'virtual']);
});
