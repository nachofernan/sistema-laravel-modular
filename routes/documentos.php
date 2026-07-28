<?php

use App\Http\Controllers\Documentos\CategoriaController;
use App\Http\Controllers\Documentos\DocumentoController;
use Illuminate\Support\Facades\Route;

Route::prefix('documentos')->group(function () {
    Route::get('/', function () {
        return redirect()->route('documentos.documentos.index');
    })->name('documentos');
});

Route::prefix('documentos')->name('documentos.')->group(function () {

    Route::group(['middleware' => ['role:Documentos/Acceso', 'PasswordExpiryCheck']], function () {

        Route::resource('/documentos', DocumentoController::class)->names('documentos');
        // Las categorías se editan desde el listado (componente Livewire) y no se borran:
        // borrar una arrastraría sus documentos por la FK en cascada.
        Route::resource('/categorias', CategoriaController::class)
            ->only(['index', 'create', 'store', 'show'])
            ->names('categorias');

        Route::get('/documentos/{documento}/descargar', [DocumentoController::class, 'download'])->name('documentos.download');
        Route::get('/documentos/{documento}/versiones/{version}/descargar', [DocumentoController::class, 'downloadVersion'])->name('documentos.versiones.download');

    });

});
