<?php

use App\Models\Usuarios\Area;
use App\Models\Usuarios\TipoArea;

test('puede crear un tipo de área', function () {
    $tipo = TipoArea::factory()->create(['nombre' => 'Gerencia']);

    expect($tipo->id)->not->toBeNull()
        ->and($tipo->nombre)->toBe('Gerencia');
});

test('un tipo de área agrupa áreas', function () {
    $tipo = TipoArea::factory()->create();
    $area = Area::factory()->create(['tipo_area_id' => $tipo->id]);

    expect($area->tipo->id)->toBe($tipo->id)
        ->and($tipo->areas->pluck('id')->all())->toContain($area->id);
});
