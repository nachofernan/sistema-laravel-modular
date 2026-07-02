<?php

use App\Models\Inventario\Estado;

test('puede crear un estado de inventario', function () {
    $estado = Estado::factory()->create();

    expect($estado->id)->not->toBeNull()
        ->and($estado->nombre)->toBeString()->not->toBeEmpty();
});
