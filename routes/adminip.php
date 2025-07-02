<?php

use App\Http\Controllers\Adminip\CategoriaController;
use App\Http\Controllers\Adminip\IpController;
use Illuminate\Support\Facades\Route;

Route::prefix('adminip')->group(function () {
    Route::get('/', function () { return redirect()->route('adminip.ips.index'); })->name('adminip');
});

Route::prefix('adminip')->name('adminip.')->group(function () {

    Route::group(['middleware' => ['role:AdminIP/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::resource('ips', IpController::class)->names('ips');
        Route::resource('categorias', CategoriaController::class)->names('categorias');

    });
    

});