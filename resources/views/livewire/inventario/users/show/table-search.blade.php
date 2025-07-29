<div>
    <!-- Filtros -->
    <div class="mb-4">
        <div class="flex flex-wrap gap-3 items-center">
            <span class="text-sm font-medium text-gray-700">Filtrar por estado:</span>
            @foreach ($estados as $estado)
                <label class="flex items-center">
                    <input type="checkbox" 
                           wire:click.live="estado_update({{ $estado->id }})"
                           {{ in_array($estado->id, $estado_search) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ $estado->nombre }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Tabla de elementos del usuario -->
    <div class="overflow-hidden rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Elemento
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Categoría
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado de Firma
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($elementos as $elemento)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if ($elemento->categoria->icono)
                                    <div class="flex-shrink-0 h-6 w-6 mr-2">
                                        {!! $elemento->categoria->icono !!}
                                    </div>
                                @endif
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $elemento->codigo }}
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                         {{ $elemento->estado->id == 1 ? 'bg-green-100 text-green-800' : 
                                            ($elemento->estado->id == 2 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $elemento->estado->nombre }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $elemento->categoria->nombre }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-600">
                                @if ($elemento->entregaActual() && !$elemento->entregaActual()->fecha_devolucion)
                                    @if ($elemento->entregaActual()->fecha_firma)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Firmado: {{ Carbon\Carbon::create($elemento->entregaActual()->fecha_firma)->format('d-m-Y') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Aún no firmado
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Firma no solicitada
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            @can('Inventario/Elementos/Ver')
                                <a href="{{ route('inventario.elementos.show', $elemento) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver
                                </a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500">No hay elementos asignados con los filtros seleccionados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        @if($elementos->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $elementos->links() }}
            </div>
        @endif
    </div>

    <!-- Información de resultados -->
    @if($elementos->count() > 0)
        <div class="mt-4 text-sm text-gray-500">
            Mostrando {{ $elementos->count() }} de {{ $elementos->total() }} elementos asignados
        </div>
    @endif
</div>