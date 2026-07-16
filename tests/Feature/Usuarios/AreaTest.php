<?php

use App\Models\User;
use App\Models\Usuarios\Area;
use App\Models\Usuarios\TipoArea;

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

test('un área puede tener un tipo', function () {
    $tipo = TipoArea::factory()->create(['nombre' => 'Sector']);
    $area = Area::factory()->create(['tipo_area_id' => $tipo->id]);

    expect($area->tipo->nombre)->toBe('Sector');
});

test('un área puede tener un responsable', function () {
    $area = Area::factory()->create();
    $user = User::factory()->create(['area_id' => $area->id]);
    $area->update(['responsable_id' => $user->id]);

    expect($area->fresh()->responsable->id)->toBe($user->id);
});

test('un área nueva está activa por defecto', function () {
    $area = Area::factory()->create();

    expect($area->fresh()->activa)->toBeTruthy();
});

test('los hijos se ordenan por el campo orden', function () {
    $padre = Area::factory()->create();
    Area::factory()->create(['area_id' => $padre->id, 'nombre' => 'C', 'orden' => 3]);
    Area::factory()->create(['area_id' => $padre->id, 'nombre' => 'A', 'orden' => 1]);
    Area::factory()->create(['area_id' => $padre->id, 'nombre' => 'B', 'orden' => 2]);

    expect($padre->hijos->pluck('nombre')->all())->toBe(['A', 'B', 'C']);
});

test('descendantIds devuelve toda la descendencia', function () {
    $a = Area::factory()->create();
    $b = Area::factory()->create(['area_id' => $a->id]);
    $c = Area::factory()->create(['area_id' => $b->id]);
    $otra = Area::factory()->create();

    expect($a->descendantIds())->toEqualCanonicalizing([$b->id, $c->id])
        ->and($a->descendantIds())->not->toContain($otra->id);
});
