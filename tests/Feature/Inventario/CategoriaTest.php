<?php

use App\Models\Inventario\Categoria;

test('puede crear una categoría de inventario', function () {
    $categoria = Categoria::factory()->create();

    expect($categoria->id)->not->toBeNull()
        ->and($categoria->nombre)->toBeString()->not->toBeEmpty()
        ->and($categoria->prefijo)->toBeString()->not->toBeEmpty();
});
