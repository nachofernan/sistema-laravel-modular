<x-app-layout>
    <x-page-header title="{{ $categoria->nombre }}">
        <x-slot:actions>
            @can('Inventario/Categorias/Editar')
                <a href="{{ route('inventario.categorias.edit', $categoria) }}" 
                   class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-md transition-colors">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
            @endcan
            <a href="{{ route('inventario.categorias.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <!-- Información de la categoría -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex items-center">
                @if ($categoria->icono)
                    <div class="mr-4">
                        {!! $categoria->icono !!}
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $categoria->nombre }}</h2>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Prefijo: {{ $categoria->prefijo }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ count($categoria->elementos) }} {{ count($categoria->elementos) == 1 ? 'elemento' : 'elementos' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Elementos de la categoría -->
            <div class="lg:col-span-2">
                @livewire('inventario.categorias.show.table-search', ['categoria' => $categoria], key($categoria->id . microtime(true)))
            </div>

            <!-- Características -->
            <div class="space-y-6">
                <!-- Gestión de Características -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Características</h3>
                        @can('Inventario/Categorias/Editar')
                            @livewire('inventario.categorias.caracteristicas.create', ['categoria' => $categoria], key($categoria->id . microtime(true)))
                        @endcan
                    </div>

                    @if($categoria->caracteristicas->count() > 0)
                        <div class="space-y-2">
                            @foreach ($categoria->caracteristicas as $caracteristica)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $caracteristica->nombre }}</div>
                                        @if($caracteristica->con_opciones)
                                            <div class="text-xs text-gray-500">Con opciones predefinidas</div>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if ($caracteristica->con_opciones)
                                            @can('Inventario/Categorias/Editar')
                                                @livewire('inventario.categorias.caracteristicas.opciones', ['caracteristica' => $caracteristica], key($caracteristica->id . microtime(true)))
                                            @endcan
                                        @endif
                                        <div class="flex items-center">
                                            <span class="text-xs text-gray-500 mr-2">Visible:</span>
                                            <div class="w-3 h-3 rounded-full {{ $caracteristica->visible ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="h-8 w-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-sm text-gray-500">No hay características definidas</p>
                        </div>
                    @endif
                </div>

                <!-- Estadísticas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total de elementos:</span>
                            <span class="text-sm font-medium text-gray-900">{{ count($categoria->elementos) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Características definidas:</span>
                            <span class="text-sm font-medium text-gray-900">{{ count($categoria->caracteristicas) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Elementos asignados:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $categoria->elementos->whereNotNull('user_id')->count() }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Elementos disponibles:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $categoria->elementos->whereNull('user_id')->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
                    
                    <div class="space-y-3">
                        @can('Inventario/Elementos/Crear')
                            <a href="{{ route('inventario.elementos.create') }}?categoria={{ $categoria->id }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Crear Elemento
                            </a>
                        @endcan
                        
                        @can('Inventario/Categorias/Editar')
                            <a href="{{ route('inventario.categorias.edit', $categoria) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar Categoría
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>