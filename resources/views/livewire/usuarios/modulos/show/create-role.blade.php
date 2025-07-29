<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Nuevo Rol
    </button> 
    
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900">Crear Nuevo Rol</h3>
            <p class="text-sm text-gray-500">{{ $modulo->nombre }} - {{ $modulo->descripcion ?: 'Módulo del sistema' }}</p>
        </x-slot> 
        
        <x-slot name="content"> 
            <div class="space-y-6">
                <!-- Formulario de creación -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <span class="text-lg font-medium text-gray-600">{{ ucfirst($modulo->nombre) }}/</span>
                        </div>
                        <div class="flex-1">
                            <input type="text" 
                                   wire:model.live="nombre" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                   placeholder="Modelo(s)/Rol (ej: Usuarios/Administrar)">
                        </div>
                        <div class="flex-shrink-0">
                            <button wire:click="create()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors"
                                    wire:loading.attr="disabled"
                                    wire:target="create">
                                <span wire:loading.remove wire:target="create">
                                    Crear Rol
                                </span>
                                <span wire:loading wire:target="create" class="flex items-center">
                                    Creando...
                                </span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Preview del nombre completo -->
                    @if($nombre)
                        <div class="mt-3 p-3 bg-white border border-gray-200 rounded-md">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Nombre completo del rol:</span>
                            </div>
                            <div class="mt-1">
                                <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">
                                    {{ ucfirst($modulo->nombre) }}/{{ $nombre }}
                                </code>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Información sobre nomenclatura -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Guía de Nomenclatura
                            </h3>
                            <div class="mt-2 text-sm text-blue-700 space-y-2">
                                <div>
                                    <p><strong>Estructura para Roles:</strong> Módulo/Modelo(s)/Rol</p>
                                    <p class="text-xs ml-4">Ejemplo: {{ $modulo->nombre }}/Usuarios/Administrar</p>
                                </div>
                                <div class="bg-blue-100 rounded p-2 mt-2">
                                    <p class="text-xs"><strong>Nota:</strong> Los roles pueden agrupar múltiples modelos, pero cada permiso es específico para un modelo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Asignación de permisos -->
                @if($modulo->permisos()->count() > 0)
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-gray-900">Asignar Permisos al Rol</h4>
                        <span class="text-sm text-gray-500">Puede modificarse después</span>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="max-h-64 overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Permiso
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center justify-center">
                                                <input type="checkbox" 
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                       wire:click="toggleAllPermisos">
                                                <span class="ml-2">Todos</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($modulo->permisos() as $permiso)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">{{ $permiso->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <input type="checkbox" 
                                                   wire:model="permisos" 
                                                   value="{{ $permiso->name }}"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Resumen de permisos seleccionados -->
                    @if(count($permisos) > 0)
                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-2 text-sm font-medium text-green-800">
                                    {{ count($permisos) }} permiso(s) seleccionado(s)
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                No hay permisos disponibles
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Cree primero algunos permisos para este módulo antes de asignarlos a roles.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Ejemplos de roles comunes -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Ejemplos de Roles Comunes</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="space-y-1">
                            <div class="font-medium text-gray-700">Roles Administrativos:</div>
                            <div class="space-y-1 text-gray-600 text-xs">
                                <div>Usuarios/Administrar</div>
                                <div>Sistema/Configurar</div>
                                <div>Reportes/Generar</div>
                                <div>Datos/Exportar</div>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="font-medium text-gray-700">Roles Operativos:</div>
                            <div class="space-y-1 text-gray-600 text-xs">
                                <div>Contenido/Editar</div>
                                <div>Productos/Gestionar</div>
                                <div>Clientes/Atender</div>
                                <div>Ordenes/Procesar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot> 
        
        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <button wire:click="$set('open', false)" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancelar
                </button>
            </div>
        </x-slot> 
    </x-dialog-modal> 
</div>