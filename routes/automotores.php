<?php

use App\Http\Controllers\Automotores\VehiculoController;
use App\Http\Controllers\Automotores\CopresController;
use Illuminate\Support\Facades\Route;

Route::prefix('automotores')->group(function () {
    Route::get('/', function () { return redirect()->route('automotores.vehiculos.index'); })->name('automotores');
});

Route::prefix('automotores')->name('automotores.')->group(function () {

    Route::group(['middleware' => ['role:Automotores/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::resource('vehiculos', VehiculoController::class)->names('vehiculos');
        Route::resource('copres', CopresController::class)->except(['edit', 'update'])->names('copres');

    });
    

});
