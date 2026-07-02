<?php

use App\Models\Tickets\Estado;

test('puede crear un estado de ticket', function () {
    $estado = Estado::factory()->create();

    expect($estado->id)->not->toBeNull()
        ->and($estado->nombre)->toBeString()->not->toBeEmpty();
});
