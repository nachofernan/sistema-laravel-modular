<?php

namespace App\Http\Middleware;

use App\Models\Usuarios\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            $event = '';

            if ($request->is('login')) {
                $event = 'login';
            } elseif ($request->routeIs('usuarios.reset.password')) {
                $event = 'password_change';
            }

            if ($event) {
                Log::create([
                    'user_id' => $user->id,
                    'event' => $event,
                    'ip_address' => $request->ip(),
                ]);
            }
        }

        return $response;
    }
}
