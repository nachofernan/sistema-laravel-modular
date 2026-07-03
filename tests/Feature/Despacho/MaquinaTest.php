<?php

use App\Models\Despacho\Maquina;
use App\Models\Despacho\Registrador;

test('puede crear una máquina', function () {
    $maquina = Maquina::factory()->create();

    expect($maquina->id)->not->toBeNull()
        ->and($maquina->codigo)->toBeString()->not->toBeEmpty();
});

test('una máquina puede tener varios registradores', function () {
    $maquina = Maquina::factory()->create();
    $registradores = Registrador::factory()->count(2)->create();

    $maquina->registradores()->attach($registradores);

    expect($maquina->registradores)->toHaveCount(2);
});
