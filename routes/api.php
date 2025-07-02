<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ConcursoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\ProveedorController;
use App\Models\Concursos\Invitacion;

Route::post('/generate-token', [AuthController::class, 'generateToken']);

// Endpoints SIN autenticación (para login progresivo)
Route::post('/validate-provider', [AuthController::class, 'validateProvider']);

Route::middleware('verify.jwt')->group(function () {
    // Ruta para subir archivos desde PortalProveedores
    Route::post('/invitacion', function(Request $request) {
        $invitacion = Invitacion::find($request->input('invitacion'));
        if($invitacion->concurso->estado->id != 2 || $invitacion->concurso->fecha_cierre < now()) {
            return response()->json([
                'message' => 'El concurso no está abierto',
            ], 400);
        } 
        $invitacion->intencion = $request->input('intencion');
        $invitacion->save();
        return response()->json([
            'message' => 'Intención actualizada correctamente',
        ], 200);
    });

    // Ruta para subir archivos desde PortalProveedores (doc general)
    Route::post('/uploadDocumentacionGeneral', [FileController::class, 'uploadDocumentacionGeneral']);
    // Ruta para subir archivos desde PortalProveedores (doc apoderados)
    Route::post('/uploadDocumentacionApoderado', [FileController::class, 'uploadDocumentacionApoderado']);

    // Ruta para subir archivos desde PortalProveedores (doc concursos)
    Route::post('/upload', [FileController::class, 'upload']);

    // Ruta para descargar archivos de la Plataforma
    Route::get('/download', [FileController::class, 'download']);

    // Ruta para eliminar archivos de la Plataforma
    Route::get('/delete', [FileController::class, 'delete']);

    Route::get('/provider-data/{cuit}', [AuthController::class, 'getProviderData']);
    Route::get('/provider-invitations/{providerId}', [ConcursoController::class, 'getProviderInvitations']);

    // ✅ NUEVO endpoint para detalles completos de concurso
    Route::get('/concurso-details/{concursoId}/{cuit}', [ConcursoController::class, 'getConcursoDetails']);

    // ========================================
    // ✅ PROVEEDORES (NUEVO)
    // ========================================
    // Dashboard completo del proveedor
    Route::get('/proveedor-dashboard/{cuit}', [ProveedorController::class, 'getDashboardData']);
    
    // Tipos de documentos para selects
    Route::get('/documento-tipos', [ProveedorController::class, 'getDocumentTypes']);
    
    // Rubros y subrubros
    Route::get('/rubros', [ProveedorController::class, 'getRubros']);
    Route::post('/proveedor-subrubro', [ProveedorController::class, 'manageSubrubro']);
    Route::post('/proveedor-rubro-completo', [ProveedorController::class, 'manageRubroComplete']);

});