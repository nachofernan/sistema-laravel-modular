<?php

use App\Models\Usuarios\Area;

test('puede crear un área', function () {
    $area = Area::factory()->create();

    expect($area->id)->not->toBeNull()
        ->and($area->nombre)->toBeString()->not->toBeEmpty();
});

test('un área puede tener un área padre', function () {
    $padre = Area::factory()->create();
    $hija = Area::factory()->create(['area_id' => $padre->id]);

    expect($hija->padre->id)->toBe($padre->id);
});
