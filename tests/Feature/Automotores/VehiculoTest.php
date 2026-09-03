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

test('sin alerta cuando faltan más de 1000 km para el intervalo, sin service previo', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 5000]);

    expect($vehiculo->proximo_service)->toBeFalse()
        ->and($vehiculo->necesita_service)->toBeFalse();
});

test('próximo service cuando faltan entre 1 y 1000 km para el intervalo, sin service previo', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 9500]);

    expect($vehiculo->proximo_service)->toBeTrue()
        ->and($vehiculo->necesita_service)->toBeFalse();
});

test('necesita service al llegar a los 10000 km desde el último service, sin service previo', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 10500]);

    expect($vehiculo->necesita_service)->toBeTrue()
        ->and($vehiculo->proximo_service)->toBeFalse();
});

test('sin alerta si el último service fue reciente', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 9500]);
    Service::factory()->create(['vehiculo_id' => $vehiculo->id, 'kilometros' => 9000]);

    expect($vehiculo->proximo_service)->toBeFalse()
        ->and($vehiculo->necesita_service)->toBeFalse();
});

test('próximo service se calcula contra el último service, no contra el kilometraje absoluto', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 19500]);
    Service::factory()->create(['vehiculo_id' => $vehiculo->id, 'kilometros' => 10000]);

    expect($vehiculo->proximo_service)->toBeTrue()
        ->and($vehiculo->necesita_service)->toBeFalse();
});

test('necesita service cuando el kilometraje pega un salto grande de una sola actualización', function () {
    // Antes, un salto de kilometraje podía "esquivar" la ventana modular (ej. de 8000 a 14000)
    // y el sistema se saltaba el ciclo de alerta hasta casi los 20000 km. Con el chequeo
    // continuo contra el último service esto ya no puede pasar.
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 14000]);
    Service::factory()->create(['vehiculo_id' => $vehiculo->id, 'kilometros' => 3000]);

    expect($vehiculo->necesita_service)->toBeTrue();
});

test('caso reportado: vehículo con 83705 km y último service a los 72765 km necesita service', function () {
    $vehiculo = Vehiculo::factory()->create(['kilometraje' => 83705]);
    Service::factory()->create(['vehiculo_id' => $vehiculo->id, 'kilometros' => 72765]);

    expect($vehiculo->necesita_service)->toBeTrue();
});
