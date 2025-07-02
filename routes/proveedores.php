<?php

use App\Http\Controllers\Proveedores\ApoderadoController;
use App\Http\Controllers\Proveedores\BancarioController;
use App\Http\Controllers\Proveedores\ContactoController;
use App\Http\Controllers\Proveedores\DireccionController;
use App\Http\Controllers\Proveedores\DocumentoController;
use App\Http\Controllers\Proveedores\DocumentoTipoController;
use App\Http\Controllers\Proveedores\ProveedorController;
use App\Http\Controllers\Proveedores\RubroController;
use App\Http\Controllers\Proveedores\ValidacionController;
use App\Models\Proveedores\Proveedor;
use Illuminate\Support\Facades\Route;

use Barryvdh\DomPDF\Facade\Pdf;

Route::prefix('proveedores')->group(function () {
    Route::get('/', function () { return redirect()->route('proveedores.proveedors.index'); })->name('proveedores');
});

Route::prefix('proveedores')->name('proveedores.')->group(function () {

    Route::group(['middleware' => ['role:Proveedores/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::resource('proveedors', ProveedorController::class)->names('proveedors');
        Route::resource('contactos', ContactoController::class)->names('contactos');
        Route::resource('apoderados', ApoderadoController::class)->names('apoderados');
        Route::resource('direccions', DireccionController::class)->names('direccions');
        Route::resource('bancarios', BancarioController::class)->names('bancarios');

        Route::resource('validacions', ValidacionController::class)->names('validacions');
        Route::resource('documentos', DocumentoController::class)->names('documentos');
        Route::resource('documento_tipos', DocumentoTipoController::class)->names('documento_tipos');

        Route::resource('rubros', RubroController::class)->names('rubros');

        Route::get('eliminados', [ProveedorController::class, 'eliminados'])->name('proveedors.eliminados');

        Route::get('export', [ProveedorController::class, 'export'])->name('proveedors.export');
        Route::get('proveedors/{proveedor}/rubros', [ProveedorController::class, 'rubros'])->name('proveedors.rubros');

        Route::get('pdfbb/{proveedor}', function($proveedor)
        {
            $pdf = app('dompdf.wrapper');
            $data = [
                'proveedor' => Proveedor::find($proveedor),
            ];
            /* $pdf->loadHTML('<h1>' . $proveedor . '</h1>'); */
            $pdf = Pdf::loadView('proveedores.proveedors.pdf', $data);

            return $pdf->download('RP-' . substr(md5(microtime(true)), 0, 6) . '.pdf');
        })->name('pdfbb');

    });
    

});