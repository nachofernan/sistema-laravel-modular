<?php

use App\Http\Controllers\Inventario\CategoriaController;
use App\Http\Controllers\Inventario\ElementoController;
use App\Http\Controllers\Inventario\PerifericoController;
use App\Http\Controllers\Inventario\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventario')->group(function () {
    Route::get('/', function () { return redirect()->route('inventario.elementos.index'); })->name('inventario');
});

Route::prefix('inventario')->name('inventario.')->group(function () {

    Route::group(['middleware' => ['role:Inventario/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::resource('elementos', ElementoController::class)->names('elementos');
        Route::resource('categorias', CategoriaController::class)->names('categorias');
        Route::resource('users', UserController::class)->names('users');
        Route::resource('perifericos', PerifericoController::class)->names('perifericos');

    });
    

});