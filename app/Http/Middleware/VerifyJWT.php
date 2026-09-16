<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyJWT
{
    /**
     * Valida el JWT del Portal de Proveedores y expone el proveedor autenticado en el request.
     * Cubierto por: endpoint_protegido_sin_token_devuelve_401,
     * endpoint_protegido_con_token_firmado_con_otro_secreto_devuelve_401,
     * endpoint_protegido_con_token_expirado_devuelve_401 (AuthControllerTest).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                throw new \Exception('Token no proporcionado');
            }

            $decoded = JWT::decode($token, new Key(config('services.jwt.secret'), 'HS256'));
            $request->attributes->add(['proveedor_id' => $decoded->sub]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido: ' . $e->getMessage()], 401);
        }
    }
}
