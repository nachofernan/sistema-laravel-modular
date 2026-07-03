<?php

use App\Models\Automotores\Vehiculo;
use App\Models\Automotores\Service;

test('puede crear un vehículo', function () {
    $vehiculo = Vehiculo::factory()->create();

    expect($vehiculo->id)->not->toBeNull()
        ->and($vehiculo->patente)->toBeString()->not->toBeEmpty();
});

test('nombreCompleto combina marca y modelo', function () {
    $vehiculo = Vehiculo::factory()->create(['marca' => 'Ford', 'modelo' => 'Ranger']);

    expect($vehiculo->nombre_completo)->toBe('Ford Ranger');
});

test('no necesita service fuera de la ventana de alerta', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 5000]);

    expect($vehiculo->necesita_service)->toBeFalse();
});

test('necesita service dentro de la ventana sin service previo', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 9500]);

    expect($vehiculo->necesita_service)->toBeTrue();
});

test('no necesita service si el último service fue reciente', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 9500]);
    Service::factory()->create(['vehiculo_id' => $vehiculo->id, 'kilometros' => 9000]);

    expect($vehiculo->necesita_service)->toBeFalse();
});

test('necesita service si el último service quedó muy atrás', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 9500]);
    Service::factory()->create(['vehiculo_id' => $vehiculo->id, 'kilometros' => 2000]);

    expect($vehiculo->necesita_service)->toBeTrue();
});
