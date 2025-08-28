<div>
    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Filtros de Búsqueda</h3>
            <button wire:click="limpiarFiltros"
                class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Limpiar Filtros
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <!-- Búsqueda general -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Búsqueda general
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live="search" id="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ticket, CUIT...">
                </div>
            </div>

            <!-- Fecha desde -->
            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha desde
                </label>
                <input type="date" wire:model.live="fecha_desde" id="fecha_desde"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Fecha hasta -->
            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha hasta
                </label>
                <input type="date" wire:model.live="fecha_hasta" id="fecha_hasta"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Vehículo -->
            <div>
                <label for="vehiculo_filter" class="block text-sm font-medium text-gray-700 mb-2">
                    Vehículo
                </label>
                <select wire:model.live="vehiculo_filter" id="vehiculo_filter"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los vehículos</option>
                    @foreach ($vehiculos as $vehiculo)
                        <option value="{{ $vehiculo->id }}">{{ $vehiculo->marca }} {{ $vehiculo->modelo }} -
                            {{ $vehiculo->patente }}</option>
                    @endforeach
                </select>
            </div>

            <!-- CUIT -->
            <div>
                <label for="cuit_filter" class="block text-sm font-medium text-gray-700 mb-2">
                    CUIT
                </label>
                <input type="text" wire:model.live="cuit_filter" id="cuit_filter"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Filtrar por CUIT...">
            </div>
        </div>
    </div>

    <!-- Resultados -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header de la tabla -->
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Registros de COPRES
                </h3>
                <div class="flex items-center space-x-3">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $copres->total() }} registros
                    </span>
                    @can('Automotores/COPRES/Crear')
                        <a href="{{ route('automotores.copres.create') }}"
                            class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm rounded-md transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Agregar COPRES
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        @if ($copres->count() > 0)
            <!-- Tabla responsive -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehículo/KM
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ticket/CUIT
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Litros/Precio
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Importe
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                KZ
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Salida/Reentrada
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($copres as $copre)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs font-medium text-gray-900">
                                        {{ $copre->fecha->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <a href="{{ route('automotores.vehiculos.show', $copre->vehiculo) }}"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-900 transition-colors">
                                            {{ $copre->vehiculo->patente }}
                                        </a>
                                        @if($copre->vehiculo->necesita_service)
                                        <span title="Necesita service">
                                            <svg class="inline w-4 h-4 text-red-600 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7zm7.94-2.5a1 1 0 00.06-2l-2.02-.17a7.03 7.03 0 00-.7-1.7l1.23-1.66a1 1 0 00-1.32-1.5l-1.66 1.23a7.03 7.03 0 00-1.7-.7l-.17-2.02a1 1 0 00-2-.06l-.17 2.02a7.03 7.03 0 00-1.7.7l-1.66-1.23a1 1 0 00-1.5 1.32l1.23 1.66a7.03 7.03 0 00-.7 1.7l-2.02.17a1 1 0 00-.06 2l2.02.17a7.03 7.03 0 00.7 1.7l-1.23 1.66a1 1 0 001.32 1.5l1.66-1.23a7.03 7.03 0 001.7.7l.17 2.02a1 1 0 002 .06l.17-2.02a7.03 7.03 0 001.7-.7l1.66 1.23a1 1 0 001.5-1.32l-1.23-1.66a7.03 7.03 0 00.7-1.7l2.02-.17z"/>
                                            </svg>
                                        </span>
                                        @endif
                                        <div class="text-xs text-gray-500">
                                            {{ number_format($copre->km_vehiculo) ?? '-' }} km
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $copre->numero_ticket_factura }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $copre->cuit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format($copre->litros, 2) }} L</div>
                                    <div class="text-xs text-gray-500">${{ number_format($copre->precio_x_litro, 2) }} x L
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        ${{ number_format($copre->importe_final, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                    @if ($copre->kz)
                                        {{ $copre->kz }}
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                    @if ($copre->salida)
                                        {{ $copre->salida->format('d/m/Y') }}
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                <br>
                                    @if ($copre->reentrada)
                                        {{ $copre->reentrada->format('d/m/Y') }}
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        @can('Automotores/COPRES/Editar')
                                            <button wire:click="openEditModal({{ $copre->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                                title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('Automotores/COPRES/Eliminar')
                                            <button wire:click="openDeleteModal({{ $copre->id }})"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
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
                {{ $copres->links() }}
            </div>
        @else
            <!-- Estado vacío -->
            <div class="px-6 py-12 text-center">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron registros de COPRES</h3>
                <p class="text-gray-500 mb-6">
                    @if ($search || $fecha_desde || $fecha_hasta || $vehiculo_filter || $cuit_filter)
                        No hay registros que coincidan con los filtros aplicados
                    @else
                        No hay registros de COPRES en el sistema
                    @endif
                </p>
                @can('Automotores/COPRES/Crear')
                    <a href="{{ route('automotores.copres.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Registrar Primera COPRES
                    </a>
                @endcan
            </div>
        @endif
    </div>

    <!-- Modales -->
    @livewire('automotores.copres.edit.modal')
    @livewire('automotores.copres.delete.modal')
</div>
