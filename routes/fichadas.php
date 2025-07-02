<?php

use App\Http\Controllers\Fichadas\FichadaController;
use Illuminate\Support\Facades\Route;

Route::prefix('fichadas')->group(function () {
    Route::get('/', function () { return redirect()->route('fichadas.fichadas.index'); })->name('fichadas');
});

Route::prefix('fichadas')->name('fichadas.')->group(function () {

    Route::group(['middleware' => ['role:Fichadas/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::resource('fichadas', FichadaController::class)->names('fichadas');

    });
    

});