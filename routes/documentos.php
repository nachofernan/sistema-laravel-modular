<?php

use App\Http\Controllers\Documentos\CategoriaController;
use App\Http\Controllers\Documentos\DocumentoController;
use Illuminate\Support\Facades\Route;

Route::prefix('documentos')->group(function () {
    Route::get('/', function () { return redirect()->route('documentos.documentos.index'); })->name('documentos');
});

Route::prefix('documentos')->name('documentos.')->group(function () {

    Route::group(['middleware' => ['role:Documentos/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::resource('/documentos', DocumentoController::class)->names('documentos');
        Route::resource('/categorias', CategoriaController::class)->names('categorias');

        Route::get('/documentos/{documento}/descargar', [DocumentoController::class, 'download'])->name('documentos.download');

    });
    

});