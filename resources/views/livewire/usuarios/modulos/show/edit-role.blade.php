<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        Editar
    </button>
    
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900">Editar Rol</h3>
            <p class="text-sm text-gray-500">{{ $role->name }}</p>
        </x-slot> 
        
        <x-slot name="content"> 
            <div class="space-y-6">
                <!-- Información actual -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-2">
                        <span class="font-medium">Rol actual:</span>
                    </div>
                    <div class="mb-4">
                        <code class="bg-white border border-gray-200 px-3 py-2 rounded text-sm font-mono block">
                            {{ $role->name }}
                        </code>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Permisos actuales:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                {{ $role->permissions->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de edición -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <span class="text-lg font-medium text-gray-600">{{ ucfirst($modulo->nombre) }}/</span>
                        </div>
                        <div class="flex-1">
                            <input type="text" 
                                   wire:model.live="nombre" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm" 
                                   placeholder="Modelo(s)/Rol">
                        </div>
                        <div class="flex-shrink-0">
                            <button wire:click="create()" 
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors"
                                    wire:loading.attr="disabled"
                                    wire:target="create">
                                <span wire:loading.remove wire:target="create">
                                    Actualizar Rol
                                </span>
                                <span wire:loading wire:target="create" class="flex items-center">
                                    Actualizando...
                                </span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Preview del nuevo nombre -->
                    @if($nombre)
                        <div class="mt-3 p-3 bg-white border border-gray-200 rounded-md">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Nuevo nombre del rol:</span>
                            </div>
                            <div class="mt-1">
                                <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">
                                    {{ ucfirst($modulo->nombre) }}/{{ $nombre }}
                                </code>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Gestión de permisos -->
                @if($modulo->permisos()->count() > 0)
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-gray-900">Editar Permisos del Rol</h4>
                        <div class="text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ count($permisos) }} seleccionados
                            </span>
                        </div>
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
                                            Estado
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
                                            @if(in_array($permiso->name, $permisos))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Asignado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    No asignado
                                                </span>
                                            @endif
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
                                <p>Cree primero algunos permisos para este módulo.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Zona de peligro -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Zona de Peligro
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p class="mb-3">Eliminar este rol puede afectar el acceso de usuarios al sistema.</p>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-1">
                                        <input type="text" 
                                               wire:model="test" 
                                               class="block w-full px-3 py-2 border border-red-300 rounded-md shadow-sm placeholder-red-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                                               placeholder="Escriba '1234' para confirmar">
                                    </div>
                                    <button wire:click="delete()" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors"
                                            wire:loading.attr="disabled"
                                            wire:target="delete">
                                        <span wire:loading.remove wire:target="delete">
                                            Eliminar Rol
                                        </span>
                                        <span wire:loading wire:target="delete" class="flex items-center">
                                            Eliminando...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                Información sobre roles
                            </h3>
                            <div class="mt-2 text-sm text-blue-700 space-y-1">
                                <p><strong>Estructura:</strong> Módulo/Modelo(s)/Rol</p>
                                <p class="text-xs">Los roles pueden agrupar múltiples modelos y tienen varios permisos asociados.</p>
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