<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Concursos\Invitacion;
use App\Models\Proveedores\Proveedor;
use Illuminate\Support\Facades\Log;

class ConcursoController extends Controller
{
    public function __construct()
    {
        $this->middleware('verify.jwt');
    }

    /**
     * Obtener invitaciones/concursos de un proveedor
     */
    public function getProviderInvitations($providerId)
    {
        $proveedor = Proveedor::find($providerId);
        
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }
        
        // Invitaciones activas
        $invitacionesActivas = $proveedor->invitaciones()
            ->with(['concurso.estado', 'concurso.documentos_requeridos'])
            ->whereHas('concurso', function ($query) {
                $query->where('estado_id', 2)
                    ->where('fecha_cierre', '>', now());
            })
            ->get();
        
        // Invitaciones finalizadas (donde presentó oferta)
        $invitacionesFinalizadas = $proveedor->invitaciones()
            ->with(['concurso.estado'])
            ->where('intencion', 3)
            ->whereHas('concurso', function ($query) {
                $query->whereIn('estado_id', [3, 4])
                    ->orWhere(function ($q) {
                        $q->where('estado_id', 2)
                          ->where('fecha_cierre', '<', now());
                    });
            })
            ->get();
        
        return response()->json([
            'invitaciones_activas' => $invitacionesActivas,
            'invitaciones_finalizadas' => $invitacionesFinalizadas
        ]);
    }

    /**
     * Obtener detalles completos de un concurso para un proveedor específico
     */
    public function getConcursoDetails($concursoId, $cuit)
    {
        try {
            // Buscar el proveedor
            $proveedor = \App\Models\Proveedores\Proveedor::where('cuit', $cuit)->first();
            
            if (!$proveedor) {
                return response()->json(['error' => 'Proveedor no encontrado'], 404);
            }
            
            // Buscar el concurso con todas sus relaciones
            $concurso = \App\Models\Concursos\Concurso::with([
                'estado',
                'documentos_requeridos.tipo_documento_proveedor',
                'documentos.documentoTipo',
                'contactos',
                'sedes',
                'prorrogas'
            ])->find($concursoId);
            
            if (!$concurso) {
                return response()->json(['error' => 'Concurso no encontrado'], 404);
            }
            
            // Buscar la invitación del proveedor para este concurso
            $invitacion = \App\Models\Concursos\Invitacion::with([
                'documentos.documentoTipo',
                'proveedor.apoderados.documentos',
                'proveedor.documentos.documentoTipo'
            ])
            ->where('concurso_id', $concursoId)
            ->where('proveedor_id', $proveedor->id)
            ->first();
            
            if (!$invitacion) {
                return response()->json(['error' => 'Invitación no encontrada'], 404);
            }
            
            // ✅ Preparar datos SIN referencias circulares
            $invitacionArray = $invitacion->toArray();
            $concursoArray = $concurso->toArray();
            
            // ✅ EN LUGAR de agregar referencias completas, agregar solo IDs básicos
            if (isset($invitacionArray['documentos'])) {
                foreach ($invitacionArray['documentos'] as &$documento) {
                    // Solo agregar información mínima necesaria, NO objetos completos
                    $documento['concurso_id'] = $concursoArray['id'];
                    $documento['concurso_nombre'] = $concursoArray['nombre'];
                    $documento['invitacion_id'] = $invitacionArray['id'];
                    // NO agregamos el concurso completo ni la invitación completa
                }
            }
            
            // Preparar datos del "user"
            $userData = [
                'id' => $proveedor->id,
                'username' => $proveedor->cuit,
                'name' => $proveedor->razonsocial,
                'email' => $proveedor->correo,
                'proveedor' => $proveedor->toArray()
            ];
            
            return response()->json([
                'concurso' => $concursoArray,
                'invitacion' => $invitacionArray,
                'user' => $userData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getConcursoDetails', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'concurso_id' => $concursoId,
                'cuit' => $cuit
            ]);
            
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}