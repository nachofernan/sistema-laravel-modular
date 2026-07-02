<?php

use App\Models\Capacitaciones\Capacitacion;

test('puede crear una capacitación', function () {
    $capacitacion = Capacitacion::factory()->create();

    expect($capacitacion->id)->not->toBeNull()
        ->and($capacitacion->nombre)->toBeString()->not->toBeEmpty();
});

test('una capacitación tiene fecha de inicio y fecha final', function () {
    $capacitacion = Capacitacion::factory()->create();

    expect($capacitacion->fecha_inicio)->not->toBeNull()
        ->and($capacitacion->fecha_final)->not->toBeNull();
});

test('la fecha final es posterior a la fecha de inicio', function () {
    $capacitacion = Capacitacion::factory()->create();

    expect($capacitacion->fecha_final->gte($capacitacion->fecha_inicio))->toBeTrue();
});
