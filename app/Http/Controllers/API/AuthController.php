<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\Proveedor;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    /**
     * Emite el JWT que el Portal de Proveedores usa para autenticarse en el resto de la API.
     * Cubierto por: un_proveedor_valido_obtiene_token_y_accede_a_endpoint_protegido,
     * generar_token_con_proveedor_inexistente_devuelve_404 (AuthControllerTest).
     */
    public function generateToken(Request $request)
    {
        $cuit = $request->input('cuit');
        $email = $request->input('email');
        
        // Verificar si el proveedor existe
        $proveedor = Proveedor::where('cuit', $cuit)->where('correo', $email)->first();
        
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }
        
        $token = JWT::encode([
            'sub' => $proveedor->id,
            'cuit' => $cuit,
            'email' => $email,
            'iat' => time(),
            'exp' => time() + 600 // Token válido por 10 minutos
        ], config('services.jwt.secret'), 'HS256');

        //return response();
        
        return response()->json(['token' => $token]);
    }

    /**
     * Validar si un proveedor existe (para login progresivo)
     */
    public function validateProvider(Request $request)
    {
        $request->validate([
            'cuit' => 'required|string'
        ]);
        
        $proveedor = Proveedor::where('cuit', $request->cuit)->first();
        
        if (!$proveedor) {
            return response()->json([
                'exists' => false,
                'message' => 'Proveedor no encontrado'
            ], 404);
        }
        
        return response()->json([
            'exists' => true,
            'proveedor' => [
                'id' => $proveedor->id,
                'cuit' => $proveedor->cuit,
                'razonsocial' => $proveedor->razonsocial,
                'correo' => $proveedor->correo,
                'estado' => $proveedor->estado_id
            ]
        ]);
    }

    /**
     * Obtener datos completos del proveedor (autenticado)
     */
    public function getProviderData($cuit)
    {
        $proveedor = Proveedor::with(['contactos', 'direcciones', 'documentos', 'apoderados'])
            ->where('cuit', $cuit)
            ->first();
        
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }
        
        return response()->json([
            'proveedor' => $proveedor
        ]);
    }
}
