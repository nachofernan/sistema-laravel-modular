<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Editar
    </button>
    
    <x-dialog-modal wire:model="open" maxWidth="2xl"> 
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900">Editar Permiso</h3>
            <p class="text-sm text-gray-500">{{ $permiso->name }}</p>
        </x-slot> 
        
        <x-slot name="content"> 
            <div class="space-y-6">
                <!-- Información actual -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-2">
                        <span class="font-medium">Nombre actual:</span>
                    </div>
                    <div class="mb-4">
                        <code class="bg-white border border-gray-200 px-3 py-2 rounded text-sm font-mono block">
                            {{ $permiso->name }}
                        </code>
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
                                   placeholder="Modelo/Acción">
                        </div>
                        <div class="flex-shrink-0">
                            <button wire:click="create()" 
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors"
                                    wire:loading.attr="disabled"
                                    wire:target="create">
                                <span wire:loading.remove wire:target="create">
                                    Actualizar
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
                                <span class="font-medium">Nuevo nombre del permiso:</span>
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
                            <div class="mt-2 text-sm text-blue-700 space-y-1">
                                <p><strong>Estructura:</strong> Módulo/Modelo/Acción</p>
                                <p class="text-xs">El nombre del módulo se agrega automáticamente.</p>
                            </div>
                        </div>
                    </div>
                </div>

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
                                <p class="mb-3">Eliminar este permiso puede afectar el funcionamiento del sistema.</p>
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
                                            Eliminar Permiso
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