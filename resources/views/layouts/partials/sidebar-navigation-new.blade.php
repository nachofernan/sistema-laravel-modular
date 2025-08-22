@php
use App\Services\SidebarService;

$sidebarService = app(SidebarService::class);
$currentModule = $sidebarService->getCurrentModule();
$visibleModules = $sidebarService->getVisibleModules();
@endphp

{{-- Inicializar Alpine con el módulo activo --}}
<div x-data="{ openModule: '{{ $currentModule }}', userMenuOpen: false }">

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
    <button @click="openModule = openModule === 'plataforma' ? null : 'plataforma'" 
            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors
                   {{ $currentModule === 'plataforma' ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <span>Plataforma</span>
        <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': openModule === 'plataforma'}" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="openModule === 'plataforma'" class="ml-6 mt-1 space-y-1">
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
        <a href="{{ route('home.dashboard') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ (request()->routeIs('home.dashboard') || request()->routeIs('home.capacitacions.*') || request()->routeIs('home.tickets.*') || request()->routeIs('home.encuestas.*')) ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Mi Portal
        </a>
        @else
        <div class="block px-3 py-1 text-sm text-gray-400 cursor-not-allowed flex items-center">
            <span>Mi Portal</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" class="ml-2">
                <path fill="#9CA3AF" d="M 12 1 C 8.6761905 1 6 3.6761905 6 7 L 6 8 C 4.9069372 8 4 8.9069372 4 10 L 4 20 C 4 21.093063 4.9069372 22 6 22 L 18 22 C 19.093063 22 20 21.093063 20 20 L 20 10 C 20 8.9069372 19.093063 8 18 8 L 18 7 C 18 3.6761905 15.32381 1 12 1 z M 12 3 C 14.27619 3 16 4.7238095 16 7 L 16 8 L 8 8 L 8 7 C 8 4.7238095 9.7238095 3 12 3 z M 6 10 L 18 10 L 18 20 L 6 20 L 6 10 z M 12 13 C 10.9 13 10 13.9 10 15 C 10 16.1 10.9 17 12 17 C 13.1 17 14 16.1 14 15 C 14 13.9 13.1 13 12 13 z"></path>
            </svg>
        </div>
        @endauth
        
        {{-- TitoBot --}}
        @auth
        <a href="{{ route('titobot') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('titobot') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            TitoBot
        </a>
        @endauth
    </div>
</div>

{{-- MÓDULOS DINÁMICOS --}}
@foreach ($visibleModules as $moduleKey => $moduleConfig)
    
    @if ($moduleConfig['type'] === 'dropdown')
        {{-- Módulo con submenú --}}
        <div>
            <button @click="openModule = openModule === '{{ $moduleKey }}' ? null : '{{ $moduleKey }}'" 
                    class="{{ $sidebarService->getMenuItemClasses($moduleKey, $currentModule) }}">
                <div class="flex items-center">
                    <span>{{ $moduleConfig['name'] }}</span>
                    {!! $sidebarService->getMaintenanceIndicator($moduleKey, $moduleConfig) !!}
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': openModule === '{{ $moduleKey }}'}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
            
            <div x-show="openModule === '{{ $moduleKey }}'" class="ml-6 mt-1 space-y-1">
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