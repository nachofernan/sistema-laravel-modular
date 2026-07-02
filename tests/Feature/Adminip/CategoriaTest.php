<?php

use App\Models\Adminip\Categoria;

test('puede crear una categoría de IP', function () {
    $categoria = Categoria::factory()->create();

    expect($categoria->id)->not->toBeNull()
        ->and($categoria->nombre)->toBeString()->not->toBeEmpty();
});
