<?php

use App\Models\Automotores\Copres;

test('puede crear una copres', function () {
    $copres = Copres::factory()->create();

    expect($copres->id)->not->toBeNull()
        ->and($copres->litros)->not->toBeNull();
});

test('una copres pertenece a un vehículo', function () {
    $copres = Copres::factory()->create();

    expect($copres->vehiculo)->not->toBeNull();
});
