<?php

use App\Models\Documentos\Categoria;
use App\Models\Documentos\Descarga;
use App\Models\Documentos\Documento;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * El portal público sirve documentos sin login. Que un documento no sea público
 * tiene que alcanzar para que no se entregue, aunque se conozca el ID: hasta esta
 * versión el link era la única autorización.
 */
function documentoConArchivo(array $atributos = [], ?Categoria $categoria = null): Documento
{
    $categoria ??= Categoria::factory()->hijaDe(Categoria::factory()->create())->create();

    $documento = Documento::factory()->create($atributos + ['categoria_id' => $categoria->id]);
    $documento->addMedia(UploadedFile::fake()->create('documento.pdf', 20))
        ->toMediaCollection('archivos');

    return $documento;
}

beforeEach(function () {
    Storage::fake('documentos');
});

test('un documento publico se descarga sin iniciar sesion', function () {
    $documento = documentoConArchivo();

    $this->get(route('home.documentos.download', $documento, absolute: false))->assertOk();
});

test('la descarga publica queda registrada sin usuario y con la ip', function () {
    $documento = documentoConArchivo();

    $this->get(route('home.documentos.download', $documento, absolute: false));

    $descarga = Descarga::where('documento_id', $documento->id)->first();

    expect($descarga)->not->toBeNull()
        ->and($descarga->user_id)->toBeNull()
        ->and($descarga->ip)->not->toBeEmpty();
});

test('un documento no publico no se descarga aunque se conozca el id', function () {
    $documento = documentoConArchivo(['publico' => false]);

    $this->get(route('home.documentos.download', $documento, absolute: false))->assertNotFound();

    expect(Descarga::where('documento_id', $documento->id)->count())->toBe(0);
});

test('un documento publico de una categoria interna no se descarga', function () {
    $categoria = Categoria::factory()->hijaDe(Categoria::factory()->create())->interna()->create();
    $documento = documentoConArchivo([], $categoria);

    $this->get(route('home.documentos.download', $documento, absolute: false))->assertNotFound();
});

test('la forma vieja del link respeta la misma regla', function () {
    $publico = documentoConArchivo();
    $interno = documentoConArchivo(['publico' => false]);

    $this->get(route('home.documentos.download-legacy', $publico, absolute: false))->assertOk();
    $this->get(route('home.documentos.download-legacy', $interno, absolute: false))->assertNotFound();
});

test('un documento sin archivo cargado devuelve 404 y no registra descarga', function () {
    $categoria = Categoria::factory()->hijaDe(Categoria::factory()->create())->create();
    $documento = Documento::factory()->create(['categoria_id' => $categoria->id]);

    $this->get(route('home.documentos.download', $documento, absolute: false))->assertNotFound();

    expect(Descarga::where('documento_id', $documento->id)->count())->toBe(0);
});

test('una categoria interna no se muestra en el portal publico', function () {
    $categoria = Categoria::factory()->interna()->create();

    $this->get(route('home.documentos.categoria', $categoria, absolute: false))->assertNotFound();
});

test('la categoria publica lista solo sus documentos publicos', function () {
    $padre = Categoria::factory()->create();
    $hija = Categoria::factory()->hijaDe($padre)->create();
    $publico = Documento::factory()->create(['categoria_id' => $hija->id, 'nombre' => 'Politica de regalos']);
    $interno = Documento::factory()->interno()->create(['categoria_id' => $hija->id, 'nombre' => 'Matriz interna']);

    $this->get(route('home.documentos.categoria', $padre, absolute: false))
        ->assertOk()
        ->assertSee($publico->nombre)
        ->assertDontSee($interno->nombre);
});

test('una subcategoria interna no aparece dentro de una categoria publica', function () {
    $padre = Categoria::factory()->create();
    $hija = Categoria::factory()->hijaDe($padre)->interna()->create();
    Documento::factory()->create(['categoria_id' => $hija->id, 'nombre' => 'Documento reservado']);

    $this->get(route('home.documentos.categoria', $padre, absolute: false))
        ->assertOk()
        ->assertDontSee($hija->nombre)
        ->assertDontSee('Documento reservado');
});
