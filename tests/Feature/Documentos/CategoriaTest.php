<?php

use App\Models\Documentos\Categoria;

test('puede crear una categoría de documentos', function () {
    $categoria = Categoria::factory()->create();

    expect($categoria->id)->not->toBeNull()
        ->and($categoria->nombre)->toBeString()->not->toBeEmpty();
});
