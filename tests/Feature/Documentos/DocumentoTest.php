<?php

use App\Models\Documentos\Documento;
use App\Models\Documentos\Categoria;

test('puede crear un documento', function () {
    $documento = Documento::factory()->create();

    expect($documento->id)->not->toBeNull()
        ->and($documento->nombre)->toBeString()->not->toBeEmpty();
});

test('un documento pertenece a una categoría y a un usuario', function () {
    $documento = Documento::factory()->create();

    expect($documento->categoria)->not->toBeNull()
        ->and($documento->user)->not->toBeNull();
});

test('un documento nuevo es visible por defecto', function () {
    $documento = Documento::factory()->create();

    expect((bool) $documento->visible)->toBeTrue();
});
