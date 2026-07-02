<?php

use App\Models\Tickets\Categoria;

test('puede crear una categoría de ticket', function () {
    $categoria = Categoria::factory()->create();

    expect($categoria->id)->not->toBeNull()
        ->and($categoria->nombre)->toBeString()->not->toBeEmpty();
});
