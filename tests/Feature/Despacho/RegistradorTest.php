<?php

use App\Models\Despacho\Maquina;
use App\Models\Despacho\Registrador;

test('puede crear un registrador', function () {
    $registrador = Registrador::factory()->create();

    expect($registrador->id)->not->toBeNull()
        ->and($registrador->codigo)->toBeString()->not->toBeEmpty();
});

test('un registrador puede pertenecer a varias máquinas', function () {
    $registrador = Registrador::factory()->create();
    $maquinas = Maquina::factory()->count(2)->create();

    $registrador->maquinas()->attach($maquinas);

    expect($registrador->maquinas)->toHaveCount(2);
});
