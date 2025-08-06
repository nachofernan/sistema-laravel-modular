<?php

use App\Http\Controllers\Tickets\MensajeController;
use App\Http\Controllers\Tickets\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('tickets')->group(function () {
    Route::get('/', function () { return redirect()->route('tickets.tickets.index'); })->name('tickets');
});

Route::prefix('tickets')->name('tickets.')->group(function () {

    Route::group(['middleware' => ['role:Tickets/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::get('tickets/{ticket}/documentos', [TicketController::class, 'documentos'])->name('tickets.documentos');
        Route::resource('tickets', TicketController::class)->names('tickets');
        Route::resource('mensajes', MensajeController::class)->names('mensajes');

    });
    

});