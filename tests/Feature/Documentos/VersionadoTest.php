<?php

use App\Models\Documentos\Categoria;
use App\Models\Documentos\Documento;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Reemplazar el archivo de un documento no puede perder el anterior: hasta esta
 * versión el update lo borraba del disco sin vuelta atrás.
 */
function documentoConArchivoLlamado(string $nombreArchivo): Documento
{
    $categoria = Categoria::factory()->hijaDe(Categoria::factory()->create())->create();
    $documento = Documento::factory()->create(['categoria_id' => $categoria->id, 'version' => 1]);

    $documento->reemplazarArchivo(UploadedFile::fake()->create($nombreArchivo, 20));
    $documento->save();

    return $documento->fresh();
}

beforeEach(function () {
    Storage::fake('documentos');
});

test('el primer archivo no genera una version en el historial', function () {
    $documento = documentoConArchivoLlamado('original.pdf');

    expect($documento->versiones)->toHaveCount(0)
        ->and($documento->version)->toBe(1)
        ->and($documento->archivo)->toBe('original.pdf');
});

test('reemplazar el archivo archiva el anterior y sube la version', function () {
    $documento = documentoConArchivoLlamado('original.pdf');

    $documento->reemplazarArchivo(UploadedFile::fake()->create('nuevo.pdf', 30), 'actualización anual');
    $documento->save();

    $documento = $documento->fresh();
    $version = $documento->versiones->first();

    expect($documento->version)->toBe(2)
        ->and($documento->archivo)->toBe('nuevo.pdf')
        ->and($documento->versiones)->toHaveCount(1)
        ->and($version->version)->toBe(1)
        ->and($version->archivo)->toBe('original.pdf')
        ->and($version->notas)->toBe('actualización anual');
});

test('el archivo anterior sigue existiendo en el historial', function () {
    $documento = documentoConArchivoLlamado('original.pdf');

    $documento->reemplazarArchivo(UploadedFile::fake()->create('nuevo.pdf', 30));
    $documento->save();

    $documento = $documento->fresh();

    expect($documento->getMedia('historial'))->toHaveCount(1)
        ->and($documento->getMedia('historial')->first()->file_name)->toBe('original.pdf')
        ->and($documento->versiones->first()->media())->not->toBeNull();
});

test('el documento vigente sirve siempre el ultimo archivo', function () {
    $documento = documentoConArchivoLlamado('v1.pdf');

    $documento->reemplazarArchivo(UploadedFile::fake()->create('v2.pdf', 30));
    $documento->save();
    $documento->refresh();
    $documento->reemplazarArchivo(UploadedFile::fake()->create('v3.pdf', 30));
    $documento->save();

    $documento = $documento->fresh();

    expect($documento->version)->toBe(3)
        ->and($documento->getFirstMedia('archivos')->file_name)->toBe('v3.pdf')
        ->and($documento->getMedia('archivos'))->toHaveCount(1)
        ->and($documento->versiones->pluck('version')->all())->toBe([2, 1]);
});

test('las versiones se listan de la mas nueva a la mas vieja', function () {
    $documento = documentoConArchivoLlamado('v1.pdf');

    $documento->reemplazarArchivo(UploadedFile::fake()->create('v2.pdf', 30));
    $documento->save();
    $documento->refresh();
    $documento->reemplazarArchivo(UploadedFile::fake()->create('v3.pdf', 30));
    $documento->save();

    expect($documento->fresh()->versiones->pluck('archivo')->all())->toBe(['v2.pdf', 'v1.pdf']);
});

test('dar de baja un documento conserva su historial', function () {
    $documento = documentoConArchivoLlamado('original.pdf');
    $documento->reemplazarArchivo(UploadedFile::fake()->create('nuevo.pdf', 30));
    $documento->save();

    $documento->delete();

    expect(Documento::withTrashed()->find($documento->id)->versiones)->toHaveCount(1);
});
