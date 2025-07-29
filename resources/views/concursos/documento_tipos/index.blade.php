<x-app-layout>
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <x-page-header title="Gestión de Tipos de Documentos">
            <x-slot:actions>
                @can('Concursos/DocumentoTipos/Crear')
                    <a href="{{ route('concursos.documento_tipos.create') }}"
                        class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                        + Nuevo Tipo de Documento
                    </a>
                @endcan
            </x-slot:actions>
        </x-page-header>

        <div class="grid grid-cols-12 gap-6">
            <!-- Documentos de Concursos -->
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-green-50 px-6 py-4 border-b border-green-200">
                        <h2 class="text-lg font-semibold text-green-800">Documentos de Concursos</h2>
                        <p class="text-sm text-green-600">Documentos que carga la empresa</p>
                    </div>
                    
                    @if($documentoTipos_concurso->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach ($documentoTipos_concurso as $documentoTipo)
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $documentoTipo->nombre }}</h3>
                                            @if($documentoTipo->descripcion)
                                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($documentoTipo->descripcion, 60) }}</p>
                                            @endif
                                        </div>
                                        @can('Concursos/DocumentoTipos/Editar')
                                            <a href="{{ route('concursos.documento_tipos.edit', $documentoTipo) }}"
                                                class="ml-3 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Editar
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-8 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No hay tipos de documentos de concurso</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documentos de Ofertas -->
            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-blue-50 px-6 py-4 border-b border-blue-200">
                        <h2 class="text-lg font-semibold text-blue-800">Documentos de Ofertas</h2>
                        <p class="text-sm text-blue-600">Documentos que cargan los proveedores</p>
                    </div>

                    @if($documentoTipos_ofertas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre del Documento
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Asociación
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Propiedades
                                        </th>
                                        @can('Concursos/DocumentoTipos/Editar')
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($documentoTipos_ofertas as $documentoTipo)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $documentoTipo->nombre }}</div>
                                                    @if($documentoTipo->descripcion)
                                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($documentoTipo->descripcion, 80) }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($documentoTipo->tipo_documento_proveedor)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        {{ $documentoTipo->tipo_documento_proveedor->nombre }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">Sin asociación</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-center space-x-2">
                                                    @if ($documentoTipo->obligatorio)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Obligatorio
                                                        </span>
                                                    @endif
                                                    @if ($documentoTipo->encriptado)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Encriptado
                                                        </span>
                                                    @endif
                                                    @if (!$documentoTipo->obligatorio && !$documentoTipo->encriptado)
                                                        <span class="text-xs text-gray-400">Estándar</span>
                                                    @endif
                                                </div>
                                            </td>
                                            @can('Concursos/DocumentoTipos/Editar')
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('concursos.documento_tipos.edit', $documentoTipo) }}"
                                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                                    Editar
                                                </a>
                                            </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-6 py-8 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No hay tipos de documentos de oferta</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>