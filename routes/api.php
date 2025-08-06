<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProveedorController;
use App\Http\Controllers\API\ConcursoController;

// Endpoints públicos (sin autenticación)
Route::post('/generate-token', [AuthController::class, 'generateToken']);
Route::post('/validate-provider', [AuthController::class, 'validateProvider']);

// Endpoints protegidos por JWT
Route::middleware('verify.jwt')->group(function () {
    // PROVEEDORES
    // Las rutas específicas deben ir ANTES que las rutas con parámetros
    Route::get('/proveedores/tipos-documentos', [ProveedorController::class, 'tiposDocumentos']);
    Route::get('/proveedores/tipos-rubros', [ProveedorController::class, 'tiposRubros']);
    Route::get('/proveedores/{cuit}', [ProveedorController::class, 'show']);
    Route::get('/proveedores/{cuit}/documentos-validados', [ProveedorController::class, 'ultimosDocumentosValidados']);
    Route::get('/proveedores/{cuit}/apoderados', [ProveedorController::class, 'apoderados']);
    Route::post('/proveedores/{cuit}/subrubros', [ProveedorController::class, 'syncSubrubros']);
    Route::post('/proveedores/{cuit}/documentos', [ProveedorController::class, 'subirDocumento']);
    Route::post('/proveedores/{cuit}/apoderados', [ProveedorController::class, 'subirApoderado']);
    Route::get('/proveedores/{cuit}/documentos/{documento_id}/descargar', [ProveedorController::class, 'descargarDocumento']);

    // CONCURSOS
    Route::get('/concursos', [ConcursoController::class, 'index']);
    Route::get('/concursos/{concurso_id}', [ConcursoController::class, 'show']);
    Route::patch('/concursos/{concurso_id}/invitacion', [ConcursoController::class, 'cambiarIntencion']);
    Route::post('/concursos/{concurso_id}/documentos', [ConcursoController::class, 'subirDocumento']);
    Route::get('/concursos/{concurso_id}/documentos/{documento_tipo_id}/verificar', [ConcursoController::class, 'verificarDocumentoProveedor']);
}); 