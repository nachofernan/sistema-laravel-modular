<?php

use App\Http\Controllers\Capacitaciones\CapacitacionController;
use App\Http\Controllers\Capacitaciones\DocumentoController;
use App\Http\Controllers\Capacitaciones\EncuestaController;
use App\Http\Controllers\Capacitaciones\OpcionController;
use App\Http\Controllers\Capacitaciones\PreguntaController;
use Illuminate\Support\Facades\Route;

Route::prefix('capacitaciones')->group(function () {
    Route::get('/', function () { return redirect()->route('capacitaciones.capacitacions.index'); })->name('capacitaciones');
});

Route::prefix('capacitaciones')->name('capacitaciones.')->group(function () {

    Route::group(['middleware' => ['role:Capacitaciones/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::resource('capacitacions', CapacitacionController::class)->names('capacitacions');
        Route::resource('documentos', DocumentoController::class)->names('documentos');

        Route::resource('encuestas', EncuestaController::class)->names('encuestas');
        Route::resource('preguntas', PreguntaController::class)->names('preguntas');
        Route::resource('opcions', OpcionController::class)->names('opcions');


    });
    

});