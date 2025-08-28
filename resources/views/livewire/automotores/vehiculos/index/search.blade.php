<div>
    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar vehículos
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live="search" 
                           id="search"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Buscar por marca, modelo o patente...">
                </div>
            </div>
            
            <div>
                <label for="marca_filter" class="block text-sm font-medium text-gray-700 mb-2">
                    Filtrar por marca
                </label>
                <select wire:model.live="marca_filter" 
                        id="marca_filter"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas las marcas</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca }}">{{ $marca }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="modelo_filter" class="block text-sm font-medium text-gray-700 mb-2">
                    Filtrar por modelo
                </label>
                <input type="text" 
                       wire:model.live="modelo_filter" 
                       id="modelo_filter"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Filtrar por modelo...">
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header de la tabla -->
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Lista de Vehículos
                </h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $vehiculos->total() }} vehículos
                </span>
            </div>
        </div>

        @if($vehiculos->count() > 0)
            <!-- Tabla responsive -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehículo
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patente
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                COPRES
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Registro
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($vehiculos as $vehiculo)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0H4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('automotores.vehiculos.show', $vehiculo) }}" 
                                               class="hover:text-blue-600 transition-colors">
                                                {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $vehiculo->nombre_completo }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $vehiculo->patente }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($vehiculo->necesita_service)
                                <span title="Necesita service">
                                    <svg class="inline w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7zm7.94-2.5a1 1 0 00.06-2l-2.02-.17a7.03 7.03 0 00-.7-1.7l1.23-1.66a1 1 0 00-1.32-1.5l-1.66 1.23a7.03 7.03 0 00-1.7-.7l-.17-2.02a1 1 0 00-2-.06l-.17 2.02a7.03 7.03 0 00-1.7.7l-1.66-1.23a1 1 0 00-1.5 1.32l1.23 1.66a7.03 7.03 0 00-.7 1.7l-2.02.17a1 1 0 00-.06 2l2.02.17a7.03 7.03 0 00.7 1.7l-1.23 1.66a1 1 0 001.32 1.5l1.66-1.23a7.03 7.03 0 001.7.7l.17 2.02a1 1 0 002 .06l.17-2.02a7.03 7.03 0 001.7-.7l1.66 1.23a1 1 0 001.5-1.32l-1.23-1.66a7.03 7.03 0 00.7-1.7l2.02-.17z"/>
                                    </svg>
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $vehiculo->copres->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $vehiculo->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('automotores.vehiculos.show', $vehiculo) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @can('Automotores/Vehículos/Editar')
                                    <a href="{{ route('automotores.vehiculos.edit', $vehiculo) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endcan
                                    @can('Automotores/Vehículos/Eliminar')
                                    <button wire:click="delete({{ $vehiculo->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors" 
                                            title="Eliminar"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este vehículo?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $vehiculos->links() }}
            </div>
        @else
            <!-- Estado vacío -->
            <div class="px-6 py-12 text-center">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0H4"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron vehículos</h3>
                <p class="text-gray-500 mb-6">
                    @if($search || $marca_filter || $modelo_filter)
                        No hay vehículos que coincidan con los filtros aplicados
                    @else
                        No hay vehículos registrados en el sistema
                    @endif
                </p>
                @can('Automotores/Vehículos/Crear')
                <a href="{{ route('automotores.vehiculos.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Registrar Primer Vehículo
                </a>
                @endcan
            </div>
        @endif
    </div>
</div>
