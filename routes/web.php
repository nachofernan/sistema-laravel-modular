<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Home\CapacitacionController;
use App\Http\Controllers\Home\EncuestaController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\MensajeController;
use App\Http\Controllers\Home\TicketController;
use App\Models\Usuarios\Modulo;
use App\Models\Usuarios\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

$prefix = env('LIVEWIRE_URL_PREFIX', 'plataforma');

Livewire::setUpdateRoute(function ($handle) use ($prefix) {
    return Route::post($prefix . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) use ($prefix) {
    return Route::get($prefix . '/livewire/livewire.js', $handle);
});

Route::get('/refresh-csrf', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
})->middleware('web');

// Rutas públicas (Buscador)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Descarga de documentación pública
Route::get('/cats/{categoria}', [HomeController::class, 'documentoCategoria'])->name('home.documentos.categoria');
Route::get('/docs/{documento}', [HomeController::class, 'documentoDownload'])->name('home.documentos.download'); // Deprecated
Route::get('/docs/{documento}/download', [HomeController::class, 'documentoDownloadWithLog'])->name('home.documentos.download-with-log');

/* Route::get('/crearSuper', function() {
    //$role = Role::create(['name' => 'Super Admin']);
    $role = Role::findByName('Super Admin');
    $role->delete();
    dd(Role::all());
    //$user = User::find(1);
    //$user->assignRole('Super Admin');
    //dd($user->getRoleNames()); 
}); */

//Rutas privadas internas
Route::middleware(['auth', 'PasswordExpiryCheck'])->prefix('home')->name('home.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::resource('tickets', TicketController::class)->names('tickets');
    Route::resource('mensajes', MensajeController::class)->names('mensajes');
    Route::resource('encuestas', EncuestaController::class)->names('encuestas');

    Route::resource('capacitacions', CapacitacionController::class)->names('capacitacions');
    Route::get('capacitacions/documentos/{documento}', [CapacitacionController::class, 'documentoDownload'])->name('capacitacions.documentos.download');
    Route::get('tickets/{ticket}/documentos', [TicketController::class, 'documentos'])->name('tickets.documentos');
});

// Aplica el middleware auth globalmente a todas las rutas de los módulos
Route::middleware(['auth', 'PasswordExpiryCheck'])->group(function () {
    /*  
    // Incluir rutas de cada módulo
    require base_path('routes/usuarios.php');
    require base_path('routes/tickets.php');
    require base_path('routes/inventario.php');
    require base_path('routes/documentos.php');
    require base_path('routes/adminip.php');
    require base_path('routes/capacitaciones.php');
    require base_path('routes/proveedores.php'); 
    */
    foreach (Modulo::where('estado', '!=', 'inactivo')->get() as $modulo) {
        $moduloLower = strtolower($modulo->nombre);
        if (File::exists(base_path("routes/{$moduloLower}.php"))) {
            require base_path("routes/{$moduloLower}.php");
        }
    }
    
    // Ruta para TitoBot
    Route::get('/titobot', [ChatController::class, 'titobot'])->name('titobot');
});

Route::get('/forgot-password', [PasswordResetController::class, 'forgotPassword'])
    ->middleware('guest')
    ->name('password.forgot');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.send');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');


    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/clear', [ChatController::class, 'clearHistory'])->name('chat.clear');


Route::get('/assign-bulk-role', function () {
    // Buscamos el rol. Si no existe, lanza excepción y se detiene.
    $role = Role::where('name', 'Proveedores/Acceso')->firstOrFail();

    $users = User::all();

    foreach ($users as $user) {
        $user->assignRole($role);
    }

    return "Éxito: Se asignó el rol '{$role->name}' a " . $users->count() . " usuarios sin afectar sus permisos previos.";
});