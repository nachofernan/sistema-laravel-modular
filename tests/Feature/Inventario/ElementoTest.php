<?php

use App\Models\Inventario\Elemento;
use App\Models\Inventario\Categoria;
use App\Models\Inventario\Estado;

test('puede crear un elemento de inventario', function () {
    $elemento = Elemento::factory()->create();

    expect($elemento->id)->not->toBeNull()
        ->and($elemento->codigo)->toBeString()->not->toBeEmpty();
});

test('un elemento pertenece a categoría y estado', function () {
    $elemento = Elemento::factory()->create();

    expect($elemento->categoria)->not->toBeNull()
        ->and($elemento->estado)->not->toBeNull();
});

test('createCodigo genera un código con el prefijo de la categoría', function () {
    $categoria = Categoria::factory()->create(['prefijo' => 'NB']);

    $codigo = Elemento::createCodigo($categoria->id);

    expect($codigo)->toStartWith('NB');
});
