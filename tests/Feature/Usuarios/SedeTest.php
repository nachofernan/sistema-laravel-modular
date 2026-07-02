<?php

use App\Models\Usuarios\Sede;

test('puede crear una sede', function () {
    $sede = Sede::factory()->create();

    expect($sede->id)->not->toBeNull()
        ->and($sede->nombre)->toBeString()->not->toBeEmpty();
});
