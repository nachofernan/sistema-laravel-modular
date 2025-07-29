<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\DocumentoTipo;
use App\Models\Proveedores\Rubro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('verify.jwt');
    }

    /**
     * Obtener datos completos del proveedor para el dashboard
     */
    public function getDashboardData($cuit)
    {
        try {
            $proveedor = Proveedor::with([
                'contactos', 
                'direcciones', 
                'documentos.documentoTipo',
                'documentos.validacion',
                'subrubros.rubro',
                'apoderados.documentos'
            ])
            ->where('cuit', $cuit)
            ->first();
            
            if (!$proveedor) {
                return response()->json(['error' => 'Proveedor no encontrado'], 404);
            }
            
            return response()->json([
                'proveedor' => $proveedor
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getDashboardData', [
                'cuit' => $cuit,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Obtener tipos de documentos para el select de subida
     */
    public function getDocumentTypes()
    {
        try {
            $documentos = DocumentoTipo::all();
            
            return response()->json([
                'documento_tipos' => $documentos
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getDocumentTypes', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Obtener rubros y subrubros para la gestión
     */
    public function getRubros(Request $request)
    {
        try {
            $search = $request->query('search', '');
            
            if (strlen($search) > 2) {
                $query = $search;
                $rubros = Rubro::with('subrubros')
                    ->where('nombre', 'LIKE', "%$query%")
                    ->get();

                // Busca en los subrubros y trae su rubro asociado
                $subrubros = \App\Models\Proveedores\Subrubro::with('rubro')
                    ->where('nombre', 'LIKE', "%$query%")
                    ->get();

                // Combina los resultados y evita duplicados
                $resultados = [];

                // Agregar rubros y sus subrubros
                foreach ($rubros as $rubro) {
                    $resultados[$rubro->id]['rubro'] = $rubro;
                    $resultados[$rubro->id]['subrubros'] = $rubro->subrubros;
                }

                // Agregar subrubros que coinciden, evitando duplicados
                foreach ($subrubros as $subrubro) {
                    $rubroId = $subrubro->rubro->id;

                    // Si el rubro ya existe en el array, agrega el subrubro si no está duplicado
                    if (isset($resultados[$rubroId])) {
                        if (!$resultados[$rubroId]['subrubros']->contains('id', $subrubro->id)) {
                            $resultados[$rubroId]['subrubros']->push($subrubro);
                        }
                    } else {
                        // Si el rubro no existe, lo agregamos con el subrubro
                        $resultados[$rubroId]['rubro'] = $subrubro->rubro;
                        $resultados[$rubroId]['subrubros'] = collect([$subrubro]);
                    }
                }
            } else {
                // Todos los rubros
                $resultados = [];
                foreach (Rubro::with('subrubros')->get() as $rubro) {
                    $resultados[] = [
                        'rubro' => $rubro,
                        'subrubros' => $rubro->subrubros,
                    ];
                }
            }

            return response()->json([
                'rubros' => array_values($resultados)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getRubros', [
                'search' => $search ?? '',
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Gestionar subrubros del proveedor
     */
    public function manageSubrubro(Request $request)
    {
        try {
            $validated = $request->validate([
                'cuit' => 'required|string',
                'subrubro_id' => 'required|integer',
                'action' => 'required|in:attach,detach'
            ]);

            $proveedor = Proveedor::where('cuit', $validated['cuit'])->first();
            
            if (!$proveedor) {
                return response()->json(['error' => 'Proveedor no encontrado'], 404);
            }

            if ($validated['action'] === 'attach') {
                $proveedor->subrubros()->syncWithoutDetaching([$validated['subrubro_id']]);
            } else {
                $proveedor->subrubros()->detach($validated['subrubro_id']);
            }

            // Recargar subrubros con rubro
            $subrubros = $proveedor->subrubros()->with('rubro')->get();

            return response()->json([
                'message' => 'Subrubro actualizado correctamente',
                'subrubros' => $subrubros
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Datos de entrada inválidos',
                'details' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error in manageSubrubro', [
                'request_data' => $request->all(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Gestionar todos los subrubros de un rubro
     */
    public function manageRubroComplete(Request $request)
    {
        try {
            $validated = $request->validate([
                'cuit' => 'required|string',
                'rubro_id' => 'required|integer',
                'action' => 'required|in:attach_all,detach_all'
            ]);

            $proveedor = Proveedor::where('cuit', $validated['cuit'])->first();
            
            if (!$proveedor) {
                return response()->json(['error' => 'Proveedor no encontrado'], 404);
            }

            $rubro = Rubro::with('subrubros')->find($validated['rubro_id']);
            if (!$rubro) {
                return response()->json(['error' => 'Rubro no encontrado'], 404);
            }

            $subrubroIds = $rubro->subrubros->pluck('id')->toArray();

            if ($validated['action'] === 'attach_all') {
                $proveedor->subrubros()->syncWithoutDetaching($subrubroIds);
            } else {
                $proveedor->subrubros()->detach($subrubroIds);
            }

            // Recargar subrubros con rubro
            $subrubros = $proveedor->subrubros()->with('rubro')->get();

            return response()->json([
                'message' => 'Rubros actualizados correctamente',
                'subrubros' => $subrubros
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Datos de entrada inválidos',
                'details' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error in manageRubroComplete', [
                'request_data' => $request->all(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}