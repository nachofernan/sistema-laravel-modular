<x-app-layout>
    <x-page-header title="Gestión de Módulo: {{ $modulo->nombre }}">
        <x-slot:subtitle>
            {{ $modulo->descripcion ?: 'Módulo del sistema' }}
        </x-slot:subtitle>
        <x-slot:actions>
            @can('Usuarios/Modulos/Editar')
                <a href="{{ route('usuarios.modulos.edit', $modulo) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Módulo
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <!-- Información del módulo -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $modulo->id }}</div>
                    <div class="text-sm text-gray-500">ID del Módulo</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $modulo->roles()->count() }}</div>
                    <div class="text-sm text-gray-500">Roles Configurados</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $modulo->permisos()->count() }}</div>
                    <div class="text-sm text-gray-500">Permisos Disponibles</div>
                </div>
                <div class="text-center">
                    @switch($modulo->estado)
                        @case('activo')
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-2 h-2 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Activo
                            </div>
                            @break
                        @case('mantenimiento')
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-2 h-2 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Mantenimiento
                            </div>
                            @break
                        @case('inactivo')
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-2 h-2 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Inactivo
                            </div>
                            @break
                    @endswitch
                    <div class="text-sm text-gray-500 mt-1">Estado Actual</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Columna izquierda: Roles y sus permisos -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Roles y Permisos</h3>
                            <p class="mt-1 text-sm text-gray-500">Gestione los roles y sus permisos asociados</p>
                        </div>
                        @livewire('usuarios.modulos.show.create-role', ['modulo' => $modulo], key($modulo->id.'-role-'.microtime(true)))
                    </div>
                </div>

                <div class="p-6">
                    @if($modulo->roles()->count() > 0)
                        <div class="space-y-4">
                            @foreach ($modulo->roles() as $role)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <!-- Header del rol -->
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $role->name }}</h4>
                                            <div class="flex items-center space-x-2">
                                                @livewire('usuarios.modulos.show.edit-role', ['modulo' => $modulo, 'role' => $role], key($role->id.'-edit-'.microtime(true)))
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Permisos del rol -->
                                    @if($role->permissions->count() > 0)
                                        <div class="px-4 py-3">
                                            @foreach ($role->permissions as $permission)
                                                <div class="text-xs font-medium text-gray-800 py-1">
                                                    - {{ $permission->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="px-4 py-3 text-center text-sm text-gray-600">
                                            Sin permisos asignados
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay roles creados</h3>
                            <p class="text-gray-500">Cree el primer rol para este módulo.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Columna derecha: Permisos disponibles -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Permisos Disponibles</h3>
                            <p class="mt-1 text-sm text-gray-500">Gestione los permisos individuales del módulo</p>
                        </div>
                        @livewire('usuarios.modulos.show.create-permission', ['modulo' => $modulo], key($modulo->id.'-permission-'.microtime(true)))
                    </div>
                </div>

                <div class="p-6">
                    @if($modulo->permisos()->count() > 0)
                        <div class="space-y-2">
                            @foreach ($modulo->permisos() as $permiso)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900">{{ $permiso->name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        @livewire('usuarios.modulos.show.edit-permission', ['modulo' => $modulo, 'permiso' => $permiso], key($permiso->id.'-edit-'.microtime(true)))
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay permisos creados</h3>
                            <p class="text-gray-500">Cree el primer permiso para este módulo.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información sobre nomenclatura -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Nomenclatura del Sistema
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Roles:</strong> {{ $modulo->nombre }}/Modelo(s)/Acción</p>
                                <p class="text-xs mt-1">Ejemplo: {{ $modulo->nombre }}/Usuarios/Administrar</p>
                            </div>
                            <div>
                                <p><strong>Permisos:</strong> {{ $modulo->nombre }}/Modelo/Acción</p>
                                <p class="text-xs mt-1">Ejemplo: {{ $modulo->nombre }}/Usuario/Crear</p>
                            </div>
                        </div>
                        <p class="mt-2 text-xs">Los roles pueden agrupar múltiples modelos, pero cada permiso es específico para un modelo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>