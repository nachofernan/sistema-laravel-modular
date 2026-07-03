<?php

use App\Models\Automotores\Service;

test('puede crear un service', function () {
    $service = Service::factory()->create();

    expect($service->id)->not->toBeNull()
        ->and($service->kilometros)->not->toBeNull();
});

test('un service pertenece a un vehículo', function () {
    $service = Service::factory()->create();

    expect($service->vehiculo)->not->toBeNull();
});

test('kilometrosFormateado agrega separador de miles y unidad', function () {
    $service = Service::factory()->create(['kilometros' => 12345]);

    expect($service->kilometros_formateado)->toBe('12,345 km');
});
