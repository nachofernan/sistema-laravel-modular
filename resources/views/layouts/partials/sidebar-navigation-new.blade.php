@php
use App\Services\SidebarService;

$sidebarService = app(SidebarService::class);
$currentModule = $sidebarService->getCurrentModule();
$visibleModules = $sidebarService->getVisibleModules();
@endphp

{{-- Script Inline para prevenir el parpadeo de Layout --}}
<script>
    (function() {
        const saved = localStorage.getItem('sidebar_open_modules');
        const currentModule = '{{ $currentModule }}';
        if (!saved && currentModule) {
            localStorage.setItem('sidebar_open_modules', JSON.stringify([currentModule]));
        }
    })();
</script>

<div x-data="{ 
    openModules: JSON.parse(localStorage.getItem('sidebar_open_modules') || '[]'),
    userMenuOpen: false,
    
    init() {
        const current = '{{ $currentModule }}';
        if (current) {
            const lastActive = localStorage.getItem('last_active_module');
            
            // Si el módulo cambió (ej: de Proveedores a Plataforma), reseteamos para abrir solo el nuevo
            if (lastActive && lastActive !== current) {
                this.openModules = [current];
            } 
            // Si es el mismo módulo pero estaba cerrado (ej: navegó dentro de la sección), lo forzamos a abrir
            else if (!this.openModules.includes(current)) {
                this.openModules.push(current);
            }
            
            localStorage.setItem('last_active_module', current);
            localStorage.setItem('sidebar_open_modules', JSON.stringify(this.openModules));
        }
    },
    
    toggleModule(moduleKey) {
        if (this.isModuleOpen(moduleKey)) {
            this.openModules = this.openModules.filter(m => m !== moduleKey);
        } else {
            this.openModules.push(moduleKey);
        }
        localStorage.setItem('sidebar_open_modules', JSON.stringify(this.openModules));
    },
    
    isModuleOpen(moduleKey) {
        return this.openModules.includes(moduleKey);
    }
}" x-cloak>

{{-- SECCIÓN USUARIO --}}
<div class="mb-4">
    @auth
    <div class="relative">
        <button @click="userMenuOpen = !userMenuOpen" 
                class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md transition-all duration-200">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="truncate max-w-[140px]">{{ Auth::user()->realname }}</span>
            </div>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': userMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        
        <div x-show="userMenuOpen" 
             x-collapse
             class="ml-6 mt-1 space-y-1 overflow-hidden">
            <a href="{{ route('usuarios.change.password') }}" 
               class="block px-3 py-1.5 text-xs text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors">
                Actualizar Contraseña
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-3 py-1.5 text-xs text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
    @else
    <a href="{{ route('login') }}" 
       class="w-full flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md">
        <span>Ingresar</span>
    </a>
    @endauth
</div>

<div class="border-t border-gray-100 mb-4"></div>

{{-- SECCIÓN PLATAFORMA --}}
<div class="mb-1">
    <button @click="toggleModule('plataforma')" 
            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-all duration-200
                   {{ $currentModule === 'plataforma' ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Plataforma</span>
        </div>
        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': isModuleOpen('plataforma')}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <div x-show="isModuleOpen('plataforma')" x-collapse>
        <div class="ml-9 mt-1 space-y-1 border-l border-gray-100">
            <a href="{{ route('home') }}" 
               class="block px-3 py-1.5 text-sm transition-colors {{ request()->routeIs('home') && !request()->has('categoria') ? 'text-blue-600 font-semibold bg-blue-50/50 border-l-2 border-blue-600 -ml-[1px]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                Buscador
            </a>
            
            @foreach (App\Models\Documentos\Categoria::whereNull('categoria_padre_id')->get() as $categoria)
            <a href="{{ route('home.documentos.categoria', $categoria) }}" 
               class="block px-3 py-1.5 text-sm transition-colors {{ url()->current() == route('home.documentos.categoria', ['categoria' => $categoria]) ? 'text-blue-600 font-semibold bg-blue-50/50 border-l-2 border-blue-600 -ml-[1px]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                {{ $categoria->nombre }}
            </a>
            @endforeach
            
            @auth
            <div class="my-2 border-t border-gray-50"></div>
            <a href="{{ route('home.dashboard') }}" 
               class="block px-3 py-1.5 text-sm transition-colors {{ (request()->routeIs('home.dashboard') || request()->routeIs('home.tickets.*')) ? 'text-blue-600 font-semibold bg-blue-50/50 border-l-2 border-blue-600 -ml-[1px]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                Sistemas
            </a>
            <a href="{{ route('home.capacitacions.index') }}" 
               class="block px-3 py-1.5 text-sm transition-colors {{ (request()->routeIs('home.capacitacions.*') || request()->routeIs('home.encuestas.*')) ? 'text-blue-600 font-semibold bg-blue-50/50 border-l-2 border-blue-600 -ml-[1px]' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                Capacitaciones
            </a>
            @endauth
        </div>
    </div>
</div>

{{-- MÓDULOS DINÁMICOS --}}
@foreach ($visibleModules as $moduleKey => $moduleConfig)
    <div class="mb-1">
        @if ($moduleConfig['type'] === 'dropdown')
            <button @click="toggleModule('{{ $moduleKey }}')" 
                    class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-all duration-200
                           {{ $sidebarService->getMenuItemClasses($moduleKey, $currentModule) }}">
                <div class="flex items-center">
                    <span>{{ $moduleConfig['name'] }}</span>
                    {!! $sidebarService->getMaintenanceIndicator($moduleKey, $moduleConfig) !!}
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': isModuleOpen('{{ $moduleKey }}')}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="isModuleOpen('{{ $moduleKey }}')" x-collapse>
                <div class="ml-9 mt-1 space-y-1 border-l border-gray-100">
                    @foreach ($sidebarService->getVisibleSubmenuItems($moduleConfig['submenu']) as $item)
                        @if ($sidebarService->routeExists($item['route']))
                            <a href="{{ route($item['route']) }}" 
                               class="block px-3 py-1.5 text-sm transition-colors {{ $sidebarService->getSubmenuItemClasses($item) }}">
                                {{ $item['name'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            @if ($sidebarService->routeExists($moduleConfig['route']))
                <a href="{{ route($moduleConfig['route']) }}" 
                   class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200
                          {{ $sidebarService->getMenuItemClasses($moduleKey, $currentModule, 'link') }}">
                    <span>{{ $moduleConfig['name'] }}</span>
                    {!! $sidebarService->getMaintenanceIndicator($moduleKey, $moduleConfig) !!}
                </a>
            @endif
        @endif
    </div>
@endforeach

</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Mejoras visuales sutiles */
    .rotate-180 { transform: rotate(180deg); }
    
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .5; transform: scale(0.8); }
    }
    .animate-pulse { animation: pulse-dot 2s infinite; }
</style>
