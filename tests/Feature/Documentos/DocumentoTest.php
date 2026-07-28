<?php

use App\Models\Documentos\Categoria;
use App\Models\Documentos\Documento;

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

    expect($documento->visible)->toBeTrue();
});

test('visible y publico se castean a booleano', function () {
    $documento = Documento::factory()->create();

    expect($documento->visible)->toBeBool()
        ->and($documento->publico)->toBeBool();
});

test('un documento publico en una rama publica es publico', function () {
    $padre = Categoria::factory()->create();
    $hija = Categoria::factory()->hijaDe($padre)->create();
    $documento = Documento::factory()->create(['categoria_id' => $hija->id]);

    expect($documento->esPublico())->toBeTrue();
});

test('un documento marcado publico no lo es si su categoria no lo es', function () {
    $padre = Categoria::factory()->create();
    $hija = Categoria::factory()->hijaDe($padre)->interna()->create();
    $documento = Documento::factory()->create(['categoria_id' => $hija->id]);

    expect($documento->publico)->toBeTrue()
        ->and($documento->esPublico())->toBeFalse();
});

test('una subcategoria publica no lo es si su categoria padre no lo es', function () {
    $padre = Categoria::factory()->interna()->create();
    $hija = Categoria::factory()->hijaDe($padre)->create();
    $documento = Documento::factory()->create(['categoria_id' => $hija->id]);

    expect($hija->publica)->toBeTrue()
        ->and($hija->esPublica())->toBeFalse()
        ->and($documento->esPublico())->toBeFalse();
});

test('dar de baja un documento no lo borra de la base', function () {
    $documento = Documento::factory()->create();

    $documento->delete();

    expect(Documento::find($documento->id))->toBeNull()
        ->and(Documento::withTrashed()->find($documento->id))->not->toBeNull();
});

test('el codigo de control de gestion se guarda tal cual', function () {
    $documento = Documento::factory()->create(['codigo' => 'PG-07.2-012-v4']);

    expect($documento->fresh()->codigo)->toBe('PG-07.2-012-v4');
});
