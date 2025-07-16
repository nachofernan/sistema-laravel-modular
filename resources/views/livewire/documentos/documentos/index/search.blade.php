<div>
    <x-page-header title="Listado de Documentos">
        <x-slot:actions>
            @can('Documentos/Documentos/Crear')
                <a href="{{ route('documentos.documentos.create') }}" 
                   class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    + Nuevo Documento
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="w-full mb-12 xl:mb-0 mx-auto space-y-6">
        @forelse ($categorias as $categoria)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header de la categoría -->
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v3H8V5z"></path>
                            </svg>
                            <h3 class="text-base font-medium text-gray-900">{{ $categoria->nombre }}</h3>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $categoria->documentos->count() }} 
                            {{ $categoria->documentos->count() == 1 ? 'documento' : 'documentos' }}
                        </span>
                    </div>
                </div>

                @if($categoria->documentos->count() > 0)
                    <!-- Tabla de documentos -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Documento
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descargas
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($categoria->documentos->sortBy('orden') as $documento)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center rounded p-1 text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $documento->orden }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    @if($documento->visible)
                                        <span class="inline-flex items-center w-4 h-4 rounded-full text-xs font-medium bg-green-500">
                                        </span>
                                    @else
                                        <span class="inline-flex items-center w-4 h-4 rounded-full text-xs font-medium bg-red-500">
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <div class="text-sm font-medium text-gray-900 max-w-xs">
                                        <a href="{{ route('documentos.documentos.show', $documento) }}" class="hover:text-blue-600 transition-colors">
                                            {{ $documento->nombre }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="text-xs text-gray-600 max-w-sm truncate">
                                        {{ $documento->descripcion ?: 'Sin descripción' }}
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-6 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $documento->descargas->count() }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ $documento->getFirstMediaUrl('archivos') }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-2 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded transition-colors">
                                            Bajar
                                        </a>
                                        @can('Documentos/Documentos/Ver')
                                            <a href="{{ route('documentos.documentos.show', $documento) }}" 
                                               class="inline-flex items-center px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition-colors">
                                                Ver
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-4 py-6 text-center">
                        <svg class="h-8 w-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm text-gray-500">No hay documentos en esta categoría</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <svg class="h-10 w-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-base font-medium text-gray-900 mb-2">No hay documentos registrados</h3>
                <p class="text-gray-500 mb-4">Comience creando el primer documento del sistema.</p>
                @can('Documentos/Documentos/Crear')
                    <a href="{{ route('documentos.documentos.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Primer Documento
                    </a>
                @endcan
            </div>
        @endforelse
    </div>
</div>