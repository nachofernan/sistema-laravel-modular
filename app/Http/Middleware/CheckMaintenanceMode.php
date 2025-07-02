<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if (env('SYSTEM_MAINTENANCE', false)) {
            // Check if the user is authenticated and has the required permission
            if(auth()->check()) {
                $user = User::find(auth()->user()->id);
                if ($user->hasPermissionTo('Usuarios/Modulos/Editar')) {
                    return $response;
                }
            }

            // Return maintenance view if system is in maintenance mode
            return response()->view('errors.mantenimiento');
        }

        return $response;
    }
}
