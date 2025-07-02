<?php

use App\Http\Controllers\MesaDeEntradas\EntradasController;
use Illuminate\Support\Facades\Route;

Route::prefix('mesadeentradas')->group(function () {
    Route::get('/', function () { return redirect()->route('mesadeentradas.entradas.index'); })->name('mesadeentradas');
});

Route::prefix('mesadeentradas')->name('mesadeentradas.')->group(function () {

    Route::group(['middleware' => ['role:MesaDeEntradas/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::resource('entradas', EntradasController::class)->names('entradas');

    });
    

});