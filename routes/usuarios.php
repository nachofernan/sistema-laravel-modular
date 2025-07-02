<?php

use App\Http\Controllers\Usuarios\AreaController;
use App\Http\Controllers\Usuarios\EmailQueueController;
use App\Http\Controllers\Usuarios\ModuloController;
use App\Http\Controllers\Usuarios\PermissionController;
use App\Http\Controllers\Usuarios\PasswordSecurityController;
use App\Http\Controllers\Usuarios\RoleController;
use App\Http\Controllers\Usuarios\SedeController;
use App\Http\Controllers\Usuarios\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::prefix('usuarios')->group(function () {
    Route::get('/', function () { return redirect()->route('usuarios.users.index'); })->name('usuarios');
});

Route::prefix('usuarios')->name('usuarios.')->group(function () {

    Route::group(['middleware' => ['auth', 'role:Usuarios/Acceso', 'PasswordExpiryCheck']], function () {
        //  Para organizar el routes, 
        //  primero tengo q ahcer la búsqueda del trashed 
        //  y después dejar que busque usuarios particulares
        Route::get('/users/trashed', [UserController::class, 'trashed'])->name('users.trashed');

        Route::resource('/users', UserController::class)->names('users');
        Route::resource('/roles', RoleController::class)->names('roles');
        Route::resource('/permissions', PermissionController::class)->names('permissions');
        Route::resource('/areas', AreaController::class)->names('areas');
        Route::resource('/sedes', SedeController::class)->names('sedes');
        Route::resource('/modulos', ModuloController::class)->names('modulos');

        Route::get('/users/{user}/roles', [UserController::class, 'roles'])->name('users.roles');
        Route::post('/users/{user}/roles', [UserController::class, 'sync_roles'])->name('users.roles');
        Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::post('/roles/{role}/permissions', [RoleController::class, 'sync_permissions'])->name('roles.permissions');

        // Panel principal de administración de correos
        //Route::get('/email-queue', [EmailQueueController::class, 'index'])->name('email-queue.index');
        Route::get('/email-queue', [EmailQueueController::class, 'index'])->name('email-queue.index');
        
        // AJAX endpoints
        Route::post('/email-queue/toggle-envios', [EmailQueueController::class, 'toggleEnvios'])->name('email-queue.toggle-envios');
        Route::post('/email-queue/reajustar-cola', [EmailQueueController::class, 'reajustarCola'])->name('email-queue.reajustar-cola');
        Route::delete('/email-queue/eliminar-job', [EmailQueueController::class, 'eliminarJob'])->name('email-queue.eliminar-job');
        Route::delete('/email-queue/limpiar-logs', [EmailQueueController::class, 'limpiarLogs'])->name('email-queue.limpiar-logs');
        Route::get('/email-queue/estadisticas', [EmailQueueController::class, 'estadisticas'])->name('email-queue.estadisticas');
        
        Route::prefix('email-queue')->name('email-queue.')->group(function () {
            // Rutas existentes... 
            Route::get('/filtro-dominio/estado', [EmailQueueController::class, 'estadoFiltroDominio'])->name('filtro.estado');
            Route::post('/filtro-dominio/toggle', [EmailQueueController::class, 'toggleFiltroDominio'])->name('filtro.toggle');
            Route::post('/filtro-dominio/probar', [EmailQueueController::class, 'probarValidacionEmail'])->name('filtro.probar');
            Route::get('/estadisticas-extendidas', [EmailQueueController::class, 'estadisticasExtendidas'])->name('estadisticas.extendidas');

            Route::get('/ejecutar-cola', function () {
                // Ejecutar el comando con fin definido
                Artisan::call('queue:work', [
                    '--queue' => 'emails-priority,emails',
                    '--stop-when-empty' => true,
                    '--max-jobs' => 10,
                    '--timeout' => 60
                ]);
                
                // Volver al panel con mensaje
                return redirect()->route('usuarios.email-queue.index')
                               ->with('success', 'Cola ejecutada correctamente. Jobs procesados.');
            })->name('ejecutar-cola');
        });
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('reset', [PasswordSecurityController::class, 'reset'])->name('change.password')->withoutMiddleware(['PasswordExpiryCheck']);
        Route::post('reset', [PasswordSecurityController::class, 'update'])->name('reset.password')->withoutMiddleware(['PasswordExpiryCheck']);
    });

});