<x-app-layout>
    <x-page-header title="{{ $categoria->nombre }}">
        <x-slot:actions>
            @can('Documentos/Categorias/Editar')
                @livewire('documentos.categorias.show.edit', ['categoria' => $categoria], key($categoria->id))
            @endcan
            <a href="{{ route('documentos.categorias.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            
            <!-- Información de la categoría -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                <h3 class="text-base font-medium text-gray-900 mb-3">Información de la Categoría</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Nombre:</span>
                        <p class="text-gray-900">{{ $categoria->nombre }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Total de documentos:</span>
                        <p class="text-gray-900">{{ $categoria->documentos->count() }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Categoría padre:</span>
                        <p class="text-gray-900">{{ $categoria->padre->nombre ?? 'Categoría principal' }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabla de documentos -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-900">Documentos en esta categoría</h3>
                </div>
                
                @if($categoria->documentos->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Visible
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
                                    Sede
                                </th>
                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($categoria->documentos->sortBy('orden') as $documento)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-900">
                                    <span class="inline-flex items-center justify-center p-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
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
                                        {{ $documento->nombre }}
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="text-xs text-gray-900 max-w-sm truncate">
                                        {{ $documento->descripcion ?: 'Sin descripción' }}
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-6 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $documento->descargas->count() }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $documento->sede->nombre ?? 'Todas' }}
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
                    <div class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="h-10 w-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-base font-medium text-gray-900 mb-2">No hay documentos en esta categoría</h3>
                            <p class="text-gray-500">Los documentos aparecerán aquí cuando sean agregados a esta categoría.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>