<?php

use Illuminate\Support\Facades\Route;

Route::prefix('despacho')->group(function () {
    Route::get('/', function () { return redirect()->route('despacho.visor'); })->name('despacho');
});

Route::prefix('despacho')->name('despacho.')->group(function () {

    Route::group(['middleware' => ['role:Despacho/Acceso', 'PasswordExpiryCheck']], function () {

        Route::get('maquinas', function () {
            return view('despacho.maquinas');
        })->name('maquinas');

        Route::get('carga-prn', function () {
            return view('despacho.carga-prn');
        })->name('carga-prn');

        Route::get('visor', function () {
            return view('despacho.visor');
        })->name('visor');

    });
    

});
