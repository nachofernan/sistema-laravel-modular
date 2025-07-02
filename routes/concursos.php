<?php

use App\Http\Controllers\Concursos\CalendarioController;
use App\Http\Controllers\Concursos\ConcursoController;
use App\Http\Controllers\Concursos\DocumentoController;
use App\Http\Controllers\Concursos\DocumentoTipoController;
use App\Http\Controllers\Concursos\ProrrogaController;
use App\Models\Concursos\Concurso;
use Illuminate\Support\Facades\Route;

use Barryvdh\DomPDF\Facade\Pdf;

Route::prefix('concursos')->group(function () {
    Route::get('/', function () { return redirect()->route('concursos.concursos.index'); })->name('concursos');
});

Route::prefix('concursos')->name('concursos.')->group(function () {

    Route::group(['middleware' => ['role:Concursos/Acceso', 'PasswordExpiryCheck']], function () {
        
        Route::get('concursos/terminados', [ConcursoController::class, 'terminados'])->name('concursos.terminados');
        Route::resource('concursos', ConcursoController::class)->names('concursos');
        Route::resource('documentos', DocumentoController::class)->names('documentos');
        Route::resource('prorrogas', ProrrogaController::class)->names('prorrogas');

        Route::resource('documento_tipos', DocumentoTipoController::class)->names('documento_tipos');

        Route::get('download/{documento}/', [DocumentoController::class, 'downloadDocument'])->name('downloadDocument');

        Route::get('pdfbb/{concurso}', function($concurso)
        {
            $concurso = Concurso::find($concurso);
            $pdf = app('dompdf.wrapper');
            $data = [
                'concurso' => $concurso,
            ];
            /* $pdf->loadHTML('<h1>' . $concurso . '</h1>'); */
            $pdf = Pdf::loadView('concursos.concursos.pdf', $data);

            if($concurso->estado_id >= 3) {
                $nombre_archivo = 'Resumen_Apertura_CP-' . $concurso->numero . '.pdf';
            } else {
                $nombre_archivo = 'Resumen_CP-' . ($concurso->numero ?? 'sin_numero') . '.pdf';
            }
            return $pdf->download($nombre_archivo);
        })->name('pdfbb');

         // Ruta para el calendario
         Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario');
         Route::get('/calendario/dia/{fecha}', [CalendarioController::class, 'dia'])->name('calendario.dia');

    });
    

});