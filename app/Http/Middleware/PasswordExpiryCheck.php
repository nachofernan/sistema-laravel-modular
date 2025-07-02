<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordExpiryCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user(); // Obtiene el usuario actual
        
        if ($user) {
            $passwordSecurity = $user->passwordSecurity; // Asumiendo que tienes una relación definida

            if ($passwordSecurity) {
                //$date = \Carbon\Carbon::create()
                $passwordExpiryDate = \Carbon\Carbon::create($passwordSecurity->password_updated_at)->addDays($passwordSecurity->password_expiry_days);

                if (\Carbon\Carbon::now() > $passwordExpiryDate) {
                    // La contraseña ha caducado
                    // Puedes redirigir al usuario a una página de cambio de contraseña
                    return redirect()->route('usuarios.change.password');
                }
            }
        }

        return $next($request);
    }
}
