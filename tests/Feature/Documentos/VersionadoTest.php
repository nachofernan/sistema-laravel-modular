<?php

use App\Livewire\Documentos\Documentos\Show\NuevaVersion;
use App\Models\Documentos\Categoria;
use App\Models\Documentos\Documento;
use App\Models\User;
use App\Models\Usuarios\Permission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;

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

/**
 * Usuario con el permiso que exige el modal de nueva versión. El permiso ya existe
 * en la base de dev; firstOrCreate lo reutiliza si está.
 */
function usuarioQueEditaDocumentos(): User
{
    $permiso = Permission::firstOrCreate(['name' => 'Documentos/Documentos/Editar', 'guard_name' => 'web']);
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $user = User::factory()->create();
    $user->givePermissionTo($permiso);

    return $user;
}

beforeEach(function () {
    Storage::fake('documentos');
    Storage::fake('local');
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

test('el modal de nueva version reemplaza el archivo y sube la version', function () {
    $this->actingAs(usuarioQueEditaDocumentos());
    $documento = documentoConArchivoLlamado('original.pdf');

    Livewire::test(NuevaVersion::class, ['documento' => $documento])
        ->set('archivo', UploadedFile::fake()->create('nuevo.pdf', 30))
        ->set('notas', 'actualización anual')
        ->call('guardar')
        ->assertRedirect(route('documentos.documentos.show', $documento, absolute: false));

    $documento = $documento->fresh();

    expect($documento->version)->toBe(2)
        ->and($documento->archivo)->toBe('nuevo.pdf')
        ->and($documento->versiones->first()->archivo)->toBe('original.pdf')
        ->and($documento->versiones->first()->notas)->toBe('actualización anual');
});

test('el modal de nueva version actualiza el codigo', function () {
    $this->actingAs(usuarioQueEditaDocumentos());
    $documento = documentoConArchivoLlamado('original.pdf');
    $documento->update(['codigo' => 'PG-07.2-012_v3']);

    Livewire::test(NuevaVersion::class, ['documento' => $documento])
        ->set('archivo', UploadedFile::fake()->create('nuevo.pdf', 30))
        ->set('codigo', 'PG-07.2-012_v4')
        ->call('guardar');

    expect($documento->fresh()->codigo)->toBe('PG-07.2-012_v4');
});

test('el modal de nueva version exige el archivo', function () {
    $this->actingAs(usuarioQueEditaDocumentos());
    $documento = documentoConArchivoLlamado('original.pdf');

    Livewire::test(NuevaVersion::class, ['documento' => $documento])
        ->call('guardar')
        ->assertHasErrors(['archivo' => 'required']);

    expect($documento->fresh()->version)->toBe(1);
});

test('un usuario sin permiso no puede subir una version nueva', function () {
    $this->actingAs(User::factory()->create());
    $documento = documentoConArchivoLlamado('original.pdf');

    Livewire::test(NuevaVersion::class, ['documento' => $documento])
        ->set('archivo', UploadedFile::fake()->create('nuevo.pdf', 30))
        ->call('guardar')
        ->assertForbidden();

    expect($documento->fresh()->version)->toBe(1);
});
