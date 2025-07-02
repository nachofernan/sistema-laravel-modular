<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Proveedores\Proveedor;
use Illuminate\Http\Request; 
use Firebase\JWT\JWT; // Esto tengo que instalarlo en la plataforma del 8.80!!!

class AuthController extends Controller
{
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
        ], env('JWT_SECRET'), 'HS256');

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
