<?php

use App\Models\Inventario\Entrega;
use App\Models\Inventario\Elemento;
use App\Models\User;

test('puede crear una entrega', function () {
    $entrega = Entrega::factory()->create();

    expect($entrega->id)->not->toBeNull()
        ->and($entrega->fecha_entrega)->not->toBeNull();
});

test('una entrega pertenece a un elemento y a un usuario', function () {
    $entrega = Entrega::factory()->create();

    expect($entrega->elemento)->not->toBeNull()
        ->and($entrega->user)->not->toBeNull();
});

test('una entrega activa no tiene fecha de devolución', function () {
    $entrega = Entrega::factory()->create(['fecha_devolucion' => null]);

    expect($entrega->fecha_devolucion)->toBeNull();
});
