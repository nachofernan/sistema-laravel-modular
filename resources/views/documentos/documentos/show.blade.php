<x-app-layout>
    <x-page-header title="{{ $documento->nombre }}">
        <x-slot:actions>
            @can('Documentos/Documentos/Editar')
                <a href="{{ route('documentos.documentos.edit', $documento) }}" 
                   class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-md transition-colors">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Información del documento -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Datos del Documento</h3>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Nombre:</div>
                        <div class="col-span-2 text-sm text-gray-900 font-medium">{{ $documento->nombre }}</div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Descripción:</div>
                        <div class="col-span-2 text-sm text-gray-900">{{ $documento->descripcion ?: 'Sin descripción' }}</div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Versión:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            @if($documento->version)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $documento->version }}
                                </span>
                            @else
                                <span class="text-gray-400">Sin versión</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Categoría:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $documento->categoria->nombre }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Sede:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $documento->sede->nombre ?? 'Todas las sedes' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Orden:</div>
                        <div class="col-span-2 text-sm text-gray-900">{{ $documento->orden }}</div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Visible:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            @if($documento->visible)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-1.5 h-1.5 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Visible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-1.5 h-1.5 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Oculto
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de archivo y estadísticas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Estadísticas y Archivo</h3>
                </div>

                <div class="space-y-4">
                    <!-- Estadísticas de descargas -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-900">Total de Descargas</p>
                                <p class="text-2xl font-bold text-blue-600">{{ count($documento->descargas) }}</p>
                            </div>
                            <div class="text-blue-400">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Usuario Creador:</div>
                        <div class="col-span-2 text-sm text-gray-900 font-medium">{{ $documento->user->realname }}</div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Cargado:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            {{ $documento->archivo_uploaded_at ? \Carbon\Carbon::parse($documento->archivo_uploaded_at)->format('d-m-Y H:i') : 'No registrado' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Archivo:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            <a href="{{ route('documentos.documentos.download', $documento) }}" 
                               target="_blank" 
                               class="text-blue-600 hover:text-blue-800 underline font-medium">
                                {{ $documento->archivo }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Tipo - Extensión:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $documento->mimeType }} - {{ $documento->extension }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Botón de descarga prominente -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('documentos.documentos.download', $documento) }}" 
                       target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar Documento
                    </a>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Información sobre el documento
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Este documento está categorizado en <strong>{{ $documento->categoria->padre->nombre ?? $documento->categoria->nombre }}</strong> → <strong>{{ $documento->categoria->nombre }}</strong>. Las descargas se registran automáticamente para fines estadísticos y de auditoría.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>