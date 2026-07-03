<?php

use App\Models\Despacho\Lectura;
use App\Models\Despacho\Maquina;
use App\Models\Despacho\Registrador;

test('puede crear una lectura', function () {
    $lectura = Lectura::factory()->create();

    expect($lectura->id)->not->toBeNull()
        ->and($lectura->valor_crudo)->not->toBeNull();
});

test('una lectura pertenece a un registrador', function () {
    $lectura = Lectura::factory()->create();

    expect($lectura->registrador)->not->toBeNull();
});

test('se pueden obtener las lecturas de todos los registradores de una máquina', function () {
    $maquina = Maquina::factory()->create();
    $registrador = Registrador::factory()->create();
    $maquina->registradores()->attach($registrador);

    Lectura::factory()->count(3)->create(['registrador_id' => $registrador->id]);

    $lecturas = Lectura::whereIn('registrador_id', $maquina->registradores->pluck('id'))->get();

    expect($lecturas)->toHaveCount(3);
});
