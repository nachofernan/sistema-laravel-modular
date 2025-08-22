{{-- Detectar módulo activo automáticamente --}}
@php
$currentModule = null;
$routeName = request()->route()->getName();

if (str_starts_with($routeName, 'usuarios.')) $currentModule = 'usuarios';
elseif (str_starts_with($routeName, 'proveedores.')) $currentModule = 'proveedores';
elseif (str_starts_with($routeName, 'concursos.')) $currentModule = 'concursos';
elseif (str_starts_with($routeName, 'inventario.')) $currentModule = 'inventario';
elseif (str_starts_with($routeName, 'documentos.')) $currentModule = 'documentos';
elseif (str_starts_with($routeName, 'tickets.')) $currentModule = 'tickets';
/* elseif (str_starts_with($routeName, 'mesadeentradas.')) $currentModule = 'mesadeentradas'; */
elseif (str_starts_with($routeName, 'capacitaciones.')) $currentModule = 'capacitaciones';
elseif (str_starts_with($routeName, 'fichadas.')) $currentModule = 'fichadas';
elseif (str_starts_with($routeName, 'adminip.')) $currentModule = 'adminip';
elseif (str_starts_with($routeName, 'home.') || $routeName === 'home' || $routeName === 'titobot') $currentModule = 'plataforma';
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

{{-- MÓDULOS ADMINISTRATIVOS --}}

{{-- Módulo: Usuarios --}}
@canany(['Usuarios/Usuarios/Ver', 'Usuarios/Areas/Ver', 'Usuarios/Sedes/Ver', 'Usuarios/Modulos/Ver'])
@php
$moduloUsuarios = App\Models\Usuarios\Modulo::where('nombre', 'Usuarios')->first();
@endphp
<div>
    <button @click="openModule = openModule === 'usuarios' ? null : 'usuarios'" 
            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors
                   {{ $currentModule === 'usuarios' ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <div class="flex items-center">
            <span>Usuarios</span>
            @if ($moduloUsuarios && $moduloUsuarios->estado == 'mantenimiento')
            <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
            @endif
        </div>
        <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': openModule === 'usuarios'}" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="openModule === 'usuarios'" class="ml-6 mt-1 space-y-1">
        @can('Usuarios/Usuarios/Ver')
        <a href="{{ route('usuarios.users.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('usuarios.users.*') && !request()->routeIs('usuarios.users.trashed') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Usuarios
        </a>
        @endcan
        @can('Usuarios/Usuarios/Eliminar')
        <a href="{{ route('usuarios.users.trashed') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('usuarios.users.trashed') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Borrados
        </a>
        @endcan
        @can('Usuarios/Areas/Ver')
        <a href="{{ route('usuarios.areas.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('usuarios.areas.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Areas
        </a>
        @endcan
        @can('Usuarios/Sedes/Ver')
        <a href="{{ route('usuarios.sedes.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('usuarios.sedes.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Sedes
        </a>
        @endcan
        @can('Usuarios/Modulos/Ver')
        <a href="{{ route('usuarios.modulos.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('usuarios.modulos.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Módulos
        </a>
        <a href="{{ route('usuarios.email-queue.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('usuarios.email-queue.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Cola de Correos
        </a>
        @endcan
    </div>
</div>
@endcanany

{{-- Módulo: Proveedores --}}
@canany(['Proveedores/Proveedores/Ver', 'Proveedores/DocumentoTipos/Ver', 'Proveedores/Rubros/Ver'])
@php
$moduloProveedores = App\Models\Usuarios\Modulo::where('nombre', 'Proveedores')->first();
@endphp
<div>
    <button @click="openModule = openModule === 'proveedores' ? null : 'proveedores'" 
            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors
                   {{ $currentModule === 'proveedores' ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <div class="flex items-center">
            <span>Proveedores</span>
            @if ($moduloProveedores && $moduloProveedores->estado == 'mantenimiento')
            <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
            @endif
        </div>
        <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': openModule === 'proveedores'}" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="openModule === 'proveedores'" class="ml-6 mt-1 space-y-1">
        @can('Proveedores/Proveedores/Ver')
        <a href="{{ route('proveedores.proveedors.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('proveedores.proveedors.*') && !request()->routeIs('proveedores.proveedors.eliminados') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Proveedores
        </a>
        <a href="{{ route('proveedores.proveedors.export') }}" 
           class="block px-3 py-1 text-sm text-gray-600 hover:text-gray-900">
            Exportar Excel
        </a>
        @endcan
        @can('Proveedores/Proveedores/EditarEstado')
        <a href="{{ route('proveedores.proveedors.eliminados') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('proveedores.proveedors.eliminados') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Eliminados
        </a>
        <a href="{{ route('proveedores.validacions.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('proveedores.validacions.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Validaciones
        </a>
        @endcan
        @can('Proveedores/DocumentoTipos/Ver')
        <a href="{{ route('proveedores.documento_tipos.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('proveedores.documento_tipos.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Tipos de Documentos
        </a>
        @endcan
        @can('Proveedores/Rubros/Ver')
        <a href="{{ route('proveedores.rubros.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('proveedores.rubros.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Rubros y Subrubros
        </a>
        @endcan
    </div>
</div>
@endcanany

{{-- Módulo: Concursos --}}
@canany(['Concursos/Concursos/Ver', 'Concursos/DocumentoTipos/Ver'])
@php
$moduloConcursos = App\Models\Usuarios\Modulo::where('nombre', 'Concursos')->first();
@endphp
<div>
    <button @click="openModule = openModule === 'concursos' ? null : 'concursos'" 
            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors
                   {{ $currentModule === 'concursos' ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <div class="flex items-center">
            <span>Concursos</span>
            @if ($moduloConcursos && $moduloConcursos->estado == 'mantenimiento')
            <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
            @endif
        </div>
        <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': openModule === 'concursos'}" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="openModule === 'concursos'" class="ml-6 mt-1 space-y-1">
        @can('Concursos/Concursos/Ver')
        <a href="{{ route('concursos.concursos.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ (request()->routeIs('concursos.concursos.index') || (request()->routeIs('concursos.concursos.show') && request()->route('concurso')?->estado_id < 3) || request()->routeIs('concursos.concursos.create')) ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Concursos Activos
        </a>
        <a href="{{ route('concursos.calendario') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ (request()->routeIs('concursos.calendario') || request()->routeIs('concursos.calendario.*')) ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Calendario
        </a>
        <a href="{{ route('concursos.concursos.terminados') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ (request()->routeIs('concursos.concursos.terminados') || (request()->routeIs('concursos.concursos.show') && request()->route('concurso')?->estado_id >= 3)) ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Concursos Terminados
        </a>
        @endcan
        @can('Concursos/DocumentoTipos/Ver')
        <a href="{{ route('concursos.documento_tipos.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('concursos.documento_tipos.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Tipos de Documentos
        </a>
        @endcan
    </div>
</div>
@endcanany

{{-- Módulo: Inventario --}}
@canany(['Inventario/Elementos/Ver', 'Inventario/Categorias/Ver', 'Inventario/Usuarios/Ver', 'Inventario/Perifericos/Ver'])
@php
$moduloInventario = App\Models\Usuarios\Modulo::where('nombre', 'Inventario')->first();
@endphp
<div>
    <button @click="openModule = openModule === 'inventario' ? null : 'inventario'" 
            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors
                   {{ $currentModule === 'inventario' ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <div class="flex items-center">
            <span>Inventario</span>
            @if ($moduloInventario && $moduloInventario->estado == 'mantenimiento')
            <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
            @endif
        </div>
        <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': openModule === 'inventario'}" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="openModule === 'inventario'" class="ml-6 mt-1 space-y-1">
        @can('Inventario/Elementos/Ver')
        <a href="{{ route('inventario.elementos.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('inventario.elementos.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Inventario
        </a>
        @endcan
        @can('Inventario/Categorias/Ver')
        <a href="{{ route('inventario.categorias.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('inventario.categorias.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Categorias
        </a>
        @endcan
        @can('Inventario/Usuarios/Ver')
        <a href="{{ route('inventario.users.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('inventario.users.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Usuarios
        </a>
        @endcan
        @can('Inventario/Perifericos/Ver')
        <a href="{{ route('inventario.perifericos.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('inventario.perifericos.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Periféricos
        </a>
        @endcan
    </div>
</div>
@endcanany

{{-- Módulo: Documentos --}}
@canany(['Documentos/Documentos/Ver', 'Documentos/Categorias/Ver'])
@php
$moduloDocumentos = App\Models\Usuarios\Modulo::where('nombre', 'Documentos')->first();
@endphp
<div>
    <button @click="openModule = openModule === 'documentos' ? null : 'documentos'" 
            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors
                   {{ $currentModule === 'documentos' ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <div class="flex items-center">
            <span>Documentos</span>
            @if ($moduloDocumentos && $moduloDocumentos->estado == 'mantenimiento')
            <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
            @endif
        </div>
        <svg class="w-4 h-4 transition-transform" :class="{'rotate-90': openModule === 'documentos'}" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div x-show="openModule === 'documentos'" class="ml-6 mt-1 space-y-1">
        @can('Documentos/Documentos/Ver')
        <a href="{{ route('documentos.documentos.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('documentos.documentos.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Documentos
        </a>
        @endcan
        @can('Documentos/Categorias/Ver')
        <a href="{{ route('documentos.categorias.index') }}" 
           class="block px-3 py-1 text-sm transition-colors {{ request()->routeIs('documentos.categorias.*') ? 'text-blue-600 font-medium border-l-2 border-blue-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}">
            Categorias
        </a>
        @endcan
    </div>
</div>
@endcanany

{{-- Módulos sin submenu (enlaces directos) --}}
@can('Tickets/Tickets/Ver')
@php
$moduloTickets = App\Models\Usuarios\Modulo::where('nombre', 'Tickets')->first();
@endphp
<a href="{{ route('tickets.tickets.index') }}" 
   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('tickets.tickets.*') ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
    <div class="flex items-center">
        <span>Tickets</span>
        @if ($moduloTickets && $moduloTickets->estado == 'mantenimiento')
        <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
        @endif
    </div>
</a>
@endcan

{{-- @can('MesaDeEntradas/Entradas/Ver')
<a href="{{ route('mesadeentradas.entradas.index') }}" 
   class="block px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('mesadeentradas.entradas.*') ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
    Mesa de Entradas
</a>
@endcan --}}

@can('Capacitaciones/Capacitaciones/Ver')
@php
$moduloCapacitaciones = App\Models\Usuarios\Modulo::where('nombre', 'Capacitaciones')->first();
@endphp
<a href="{{ route('capacitaciones.capacitacions.index') }}" 
   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('capacitaciones.capacitacions.*') ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
    <div class="flex items-center">
        <span>Capacitaciones</span>
        @if ($moduloCapacitaciones && $moduloCapacitaciones->estado == 'mantenimiento')
        <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
        @endif
    </div>
</a>
@endcan

@can('Fichadas/Fichadas/Ver')
@php
$moduloFichadas = App\Models\Usuarios\Modulo::where('nombre', 'Fichadas')->first();
@endphp
<a href="{{ route('fichadas.fichadas.index') }}" 
   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('fichadas.fichadas.*') ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
    <div class="flex items-center">
        <span>Fichadas</span>
        @if ($moduloFichadas && $moduloFichadas->estado == 'mantenimiento')
        <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
        @endif
    </div>
</a>
@endcan

@can('AdminIP/IPS/Ver')
@php
$moduloAdminIP = App\Models\Usuarios\Modulo::where('nombre', 'AdminIP')->first();
@endphp
<a href="{{ route('adminip.ips.index') }}" 
   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('adminip.ips.*') ? 'text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
    <div class="flex items-center">
        <span>Admin IP</span>
        @if ($moduloAdminIP && $moduloAdminIP->estado == 'mantenimiento')
        <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0.5" title="Módulo en mantenimiento">!</span>
        @endif
    </div>
</a>
@endcan

</div>