<?php

namespace App\Services;

use App\Models\User;
use App\Models\Usuarios\Modulo;
use App\Models\Usuarios\Permission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SidebarService
{
    protected $modules;
    protected $moduleStates;

    public function __construct()
    {
        $this->modules = collect(config('sidebar.modules', []))
            ->sortBy('order');
        
        // Cache de estados de módulos para evitar múltiples consultas
        $this->moduleStates = Modulo::all()->keyBy(function($modulo) {
            return strtolower($modulo->nombre);
        });
    }

    /**
     * Obtiene todos los módulos visibles para el usuario actual
     */
    public function getVisibleModules(): Collection
    {
        return $this->modules->filter(function($moduleConfig, $moduleKey) {
            return $this->hasPermission($moduleConfig['permission_check']);
        });
    }

    /**
     * Verifica si el usuario tiene alguno de los permisos requeridos
     */
    protected function hasPermission(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $user = User::find(Auth::id());
        
        foreach ($permissions as $permission) {
            if (Permission::where('name', $permission)->exists() && $user->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene el estado de un módulo
     */
    public function getModuleState(string $moduleKey, array $moduleConfig): ?string
    {
        $moduleName = $moduleConfig['module_name'] ?? ucfirst($moduleKey);
        $moduleData = $this->moduleStates->get(strtolower($moduleName));
        
        return $moduleData?->estado;
    }

    /**
     * Verifica si un módulo está en mantenimiento
     */
    public function isInMaintenance(string $moduleKey, array $moduleConfig): bool
    {
        return $this->getModuleState($moduleKey, $moduleConfig) === 'mantenimiento';
    }

    /**
     * Genera el indicador visual de mantenimiento
     */
    public function getMaintenanceIndicator(string $moduleKey, array $moduleConfig): string
    {
        if (!$this->isInMaintenance($moduleKey, $moduleConfig)) {
            return '';
        }

        $indicatorConfig = config('sidebar.maintenance_indicator', []);
        $style = $indicatorConfig['style'] ?? 'dot';
        
        switch ($style) {
            case 'dot':
                return '<span class="ml-2 w-2 h-2 bg-red-500 rounded-full animate-pulse" title="Módulo en mantenimiento"></span>';
            
            case 'badge':
                return '<span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>';
            
            case 'icon':
                return '<svg class="ml-2 w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20" title="Módulo en mantenimiento">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>';
            
            default:
                return '<span class="ml-2 w-2 h-2 bg-red-500 rounded-full animate-pulse" title="Módulo en mantenimiento"></span>';
        }
    }

    /**
     * Verifica si una ruta está activa
     */
    public function isRouteActive($activeWhen, $excludeWhen = null): bool
    {
        // Si es una función, la ejecutamos
        if (is_callable($activeWhen)) {
            return $activeWhen();
        }

        // Si hay exclusiones y coinciden, no está activo
        if ($excludeWhen && request()->routeIs($excludeWhen)) {
            return false;
        }

        // Verificar si la ruta actual coincide con el patrón
        return request()->routeIs($activeWhen);
    }

    /**
     * Obtiene los elementos del submenú visibles para el usuario
     */
    public function getVisibleSubmenuItems(array $submenu): Collection
    {
        return collect($submenu)->filter(function($item) {
            return $this->hasPermission([$item['permission']]);
        });
    }

    /**
     * Verifica si existe la ruta especificada
     */
    public function routeExists(string $routeName): bool
    {
        return Route::has($routeName);
    }

    /**
     * Obtiene el módulo actual basado en la ruta
     */
    public function getCurrentModule(): ?string
    {
        $routeName = request()->route()?->getName();
        
        if (!$routeName) {
            return null;
        }

        // Mapeo específico para algunos casos especiales
        $moduleMap = [
            'usuarios.' => 'usuarios',
            'proveedores.' => 'proveedores',
            'concursos.' => 'concursos',
            'inventario.' => 'inventario',
            'documentos.' => 'documentos',
            'tickets.' => 'tickets',
            'capacitaciones.' => 'capacitaciones',
            'fichadas.' => 'fichadas',
            'adminip.' => 'adminip',
            'automotores.' => 'automotores',
            'home.' => 'plataforma',
            'home' => 'plataforma',
            'titobot' => 'plataforma'
        ];

        foreach ($moduleMap as $prefix => $module) {
            if (str_starts_with($routeName, $prefix) || $routeName === $prefix) {
                return $module;
            }
        }

        return 'guest';
    }

    /**
     * Obtiene las clases CSS para un elemento del menú
     */
    public function getMenuItemClasses(string $moduleKey, string $currentModule, string $type = 'dropdown'): string
    {
        $baseClasses = 'w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors';
        
        if ($type === 'link') {
            $baseClasses = 'flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors';
        }

        $activeClasses = $currentModule === $moduleKey ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100';
        
        return $baseClasses . ' ' . $activeClasses;
    }

    /**
     * Obtiene las clases CSS para un elemento del submenú
     */
    public function getSubmenuItemClasses(array $item): string
    {
        $baseClasses = 'block px-3 py-1 text-sm transition-colors';
        
        // Si es de tipo 'action', no se marca como activo
        if (($item['type'] ?? '') === 'action') {
            return $baseClasses . ' text-gray-600 hover:text-gray-900';
        }

        $isActive = $this->isRouteActive($item['active_when'], $item['exclude_when'] ?? null);
        $activeClasses = $isActive 
            ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' 
            : 'text-gray-600 hover:text-gray-900';
        
        return $baseClasses . ' ' . $activeClasses;
    }
} 