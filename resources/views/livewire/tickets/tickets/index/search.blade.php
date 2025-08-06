<div wire:poll.3s="refreshTickets({{ $lastId }})">
    <!-- Filtros de búsqueda -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Búsqueda por código -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar por código
                </label>
                <input type="text" 
                       wire:model.live="search"
                       id="search"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="Escriba el código del ticket...">
            </div>

            <!-- Filtro por categoría -->
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    Categoría
                </label>
                <select wire:model.live="categoria" 
                        id="categoria"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="0">Todas las categorías</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Estadística rápida -->
            <div class="flex items-end">
                <div class="w-full p-3 bg-blue-50 rounded-lg">
                    <div class="text-xs text-blue-600 font-medium">Tickets Activos</div>
                    <div class="text-2xl font-bold text-blue-900">
                        {{ $tickets->where('finalizado', null)->count() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros por estado -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Filtrar por estado
            </label>
            <div class="flex flex-wrap gap-3">
                @foreach ($estados as $estado)
                    <label class="flex items-center">
                        <input type="checkbox" 
                               wire:click="estado_update({{ $estado->id }})"
                               {{ in_array($estado->id, $estado_search) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">{{ $estado->nombre }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tabla de resultados -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ticket
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Categoría
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Solicitante
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Encargado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Documento
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">#{{ $ticket->codigo }}</div>
                                @if($ticket->mensajesNuevos())
                                    <div class="text-xs text-red-600 font-medium">Mensajes nuevos</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $ticket->categoria->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                         {{ $ticket->finalizado ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                {{ $ticket->estado->nombre }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-xs text-gray-900">{{ $ticket->user->realname }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-900">
                                {{ $ticket->encargado ? $ticket->encargado->realname : 'Sin asignar' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-900">
                                @if($ticket->documento)
                                    @php $media = $ticket->documento->getFirstMedia('archivos') @endphp
                                    @if($media)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Sí
                                        </span>
                                    @else
                                        <span class="text-gray-400">No disponible</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">No</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-900">
                                {{ Carbon\Carbon::create($ticket->created_at)->format('d-m-Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ Carbon\Carbon::create($ticket->created_at)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                @can('Tickets/Tickets/Ver')
                                    <a href="{{ route('tickets.tickets.show', $ticket) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver
                                        @if($ticket->mensajesNuevos())
                                            <span class="ml-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                        @endif
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron tickets</h3>
                                <p class="text-gray-500">Intente ajustar los filtros de búsqueda.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        @if($tickets->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

    <!-- Información de resultados -->
    @if($tickets->count() > 0)
        <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
            <div>
                Mostrando {{ $tickets->count() }} de {{ $tickets->total() }} tickets
            </div>
            <div class="flex space-x-4">
                <span>Abiertos: {{ $tickets->where('finalizado', null)->count() }}</span>
                <span>Cerrados: {{ $tickets->where('finalizado', '!=', null)->count() }}</span>
            </div>
        </div>
    @endif
</div>