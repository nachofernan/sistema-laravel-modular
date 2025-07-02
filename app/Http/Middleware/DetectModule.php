<?php

namespace App\Http\Middleware;

use App\Models\Usuarios\Modulo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class DetectModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define los módulos válidos
        /* $validModules = [
            'adminip', 
            'capacitaciones', 
            'documentos',
            'inventario',
            'proveedores',
            'tickets',
            'usuarios',
        ]; */
        $validModules = array();
        foreach (Modulo::where('estado', '!=', 'inactivo')->get() as $modulo) {
            $validModules[] = strtolower($modulo->nombre);
        }

        $uriSegments = $request->segments();
        
        // Asumiendo que el módulo siempre está en el índice 1 del array de segmentos
        $module = $uriSegments[0] ?? 'guest';

        // Verificar si el módulo está en la lista de módulos válidos
        if (!in_array($module, $validModules)) {
            $module = 'guest';
        }

        // Compartir la variable módulo con las vistas
        View::share('module', $module);

        // Adjuntar el módulo al request para que Livewire pueda acceder a él
        $request->attributes->set('module', $module);

        return $next($request);
    }
}
