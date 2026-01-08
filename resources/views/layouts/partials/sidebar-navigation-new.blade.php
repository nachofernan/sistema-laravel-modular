@php
use App\Services\SidebarService;

$sidebarService = app(SidebarService::class);
$currentModule = $sidebarService->getCurrentModule();
$visibleModules = $sidebarService->getVisibleModules();
@endphp

{{-- Inicializar Alpine con un array de módulos abiertos --}}
<div x-data="{ 
    openModules: ['{{ $currentModule }}'], 
    userMenuOpen: false,
    toggleModule(moduleKey) {
        const index = this.openModules.indexOf(moduleKey);
        if (index > -1) {
            this.openModules.splice(index, 1);
        } else {
            this.openModules.push(moduleKey);
        }
    },
    isModuleOpen(moduleKey) {
        return this.openModules.includes(moduleKey);
    }
}">

{{-- SECCIÓN USUARIO --}}
<div class="mb-4">
    @auth
    <div class="relative">
        <button @click="userMenuOpen = !userMenuOpen" 
                class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md">
            <span>{{ Auth::user()->realname }}</span>
            <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': userMenuOpen}" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
        </button>
        <div x-show="userMenuOpen" class="ml-6 mt-1 space-y-1">
            <a href="{{ route('usuarios.change.password') }}" 
               class="block px-3 py-1 text-sm text-gray-600 hover:text-gray-900">
                Actualizar Contraseña
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-3 py-1 text-sm text-gray-600 hover:text-gray-900">
                    Salir
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

{{-- SEPARADOR --}}
<div class="border-t border-gray-200 mb-4"></div>

{{-- SECCIÓN PLATAFORMA --}}
<div>
    <button @click="toggleModule('plataforma')" 
            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors
                   {{ $currentModule === 'plataforma' ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <span>Plataforma</span>
        <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': isModuleOpen('plataforma')}" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="isModuleOpen('plataforma')" class="ml-6 mt-1 space-y-1">
        <a href="{{ route('home') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('home') && !request()->has('categoria') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Buscador
        </a>
        
        {{-- Categorías dinámicas --}}
        @foreach (App\Models\Documentos\Categoria::whereNull('categoria_padre_id')->get() as $categoria)
        <a href="{{ route('home.documentos.categoria', $categoria) }}" 
           class="block px-3 py-1 text-sm transition-colors {{ url()->current() == route('home.documentos.categoria', ['categoria' => $categoria]) ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            {{ $categoria->nombre }}
        </a>
        @endforeach
        
        {{-- Mi Portal --}}
        @auth
        <hr>
        <a href="{{ route('home.dashboard') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ (request()->routeIs('home.dashboard') ||  request()->routeIs('home.tickets.*')) ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Sistemas
        </a>
        <a href="{{ route('home.capacitacions.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ (request()->routeIs('home.capacitacions.*') || request()->routeIs('home.encuestas.*')) ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Capacitaciones
        </a>
        {{-- <a href="{{ route('titobot') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('titobot') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            TitoBot
        </a> --}}
        @endauth
    </div>
</div>

{{-- MÓDULOS DINÁMICOS --}}
@foreach ($visibleModules as $moduleKey => $moduleConfig)
    
    @if ($moduleConfig['type'] === 'dropdown')
        {{-- Módulo con submenú --}}
        <div>
            <button @click="toggleModule('{{ $moduleKey }}')" 
                    class="{{ $sidebarService->getMenuItemClasses($moduleKey, $currentModule) }}">
                <div class="flex items-center">
                    <span>{{ $moduleConfig['name'] }}</span>
                    {!! $sidebarService->getMaintenanceIndicator($moduleKey, $moduleConfig) !!}
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': isModuleOpen('{{ $moduleKey }}')}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
            
            <div x-show="isModuleOpen('{{ $moduleKey }}')" class="ml-6 mt-1 space-y-1">
                @foreach ($sidebarService->getVisibleSubmenuItems($moduleConfig['submenu']) as $item)
                    @if ($sidebarService->routeExists($item['route']))
                        <a href="{{ route($item['route']) }}" 
                           class="{{ $sidebarService->getSubmenuItemClasses($item) }}">
                            {{ $item['name'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        
    @else
        {{-- Módulo de enlace directo --}}
        @if ($sidebarService->routeExists($moduleConfig['route']))
            <a href="{{ route($moduleConfig['route']) }}" 
               class="{{ $sidebarService->getMenuItemClasses($moduleKey, $currentModule, 'link') }}">
                <div class="flex items-center">
                    <span>{{ $moduleConfig['name'] }}</span>
                    {!! $sidebarService->getMaintenanceIndicator($moduleKey, $moduleConfig) !!}
                </div>
            </a>
        @endif
    @endif
    
@endforeach

</div>

{{-- Estilos adicionales para las nuevas opciones de indicador --}}
<style>
/* Animación para el indicador tipo 'dot' */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style> 