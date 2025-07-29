<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm rounded-md transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Nuevo Permiso
    </button> 
    
    <x-dialog-modal wire:model="open" maxWidth="2xl"> 
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900">Crear Nuevo Permiso</h3>
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
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                                   placeholder="Modelo/Acción (ej: Usuario/Crear)">
                        </div>
                        <div class="flex-shrink-0">
                            <button wire:click="create()" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors"
                                    wire:loading.attr="disabled"
                                    wire:target="create">
                                <span wire:loading.remove wire:target="create">
                                    Crear
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
                                <span class="font-medium">Nombre completo del permiso:</span>
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
                                <div>
                                    <p><strong>Estructura para Permisos:</strong> Módulo/Modelo/Acción</p>
                                    <p class="text-xs ml-4">Ejemplo: {{ $modulo->nombre }}/Usuario/Crear</p>
                                </div>
                                <div class="bg-blue-100 rounded p-2 mt-2">
                                    <p class="text-xs"><strong>Nota:</strong> No incluya el nombre del módulo en el campo, se agregará automáticamente.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ejemplos comunes -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Ejemplos de Permisos Comunes</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="space-y-1">
                            <div class="font-medium text-gray-700">Operaciones CRUD:</div>
                            <div class="space-y-1 text-gray-600 text-xs">
                                <div>Modelo/Crear</div>
                                <div>Modelo/Editar</div>
                                <div>Modelo/Eliminar</div>
                                <div>Modelo/Ver</div>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="font-medium text-gray-700">Operaciones Especiales:</div>
                            <div class="space-y-1 text-gray-600 text-xs">
                                <div>Modelo/Exportar</div>
                                <div>Modelo/Importar</div>
                                <div>Modelo/Aprobar</div>
                                <div>Modelo/Reportes</div>
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