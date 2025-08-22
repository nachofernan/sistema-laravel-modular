<x-app-layout>
    <x-page-header title="{{ $proveedor->razonsocial }}">
        <x-slot:actions>
            @canany(['Proveedores/Proveedores/Editar', 'Proveedores/Proveedores/EditarEstado'])
                <a href="{{ route('proveedores.proveedors.edit', $proveedor) }}" 
                   class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    Editar Proveedor
                </a>
            @endcanany
            @livewire('proveedores.proveedors.show.notificar-vencimiento', ['proveedor' => $proveedor])
            <a href="{{ route('proveedores.pdfbb', $proveedor) }}" 
               class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm rounded-md transition-colors inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1">
                    <path fill-rule="evenodd" d="M4 2a1.5 1.5 0 0 0-1.5 1.5v9A1.5 1.5 0 0 0 4 14h8a1.5 1.5 0 0 0 1.5-1.5V6.621a1.5 1.5 0 0 0-.44-1.06L9.94 2.439A1.5 1.5 0 0 0 8.878 2H4Zm4 3.5a.75.75 0 0 1 .75.75v2.69l.72-.72a.75.75 0 1 1 1.06 1.06l-2 2a.75.75 0 0 1-1.06 0l-2-2a.75.75 0 0 1 1.06-1.06l.72.72V6.25A.75.75 0 0 1 8 5.5Z" clip-rule="evenodd" />
                </svg>
                Descargar PDF
            </a>
        </x-slot:actions>
    </x-page-header>

    @if ($proveedor->litigio)
        <div class="w-full max-w-7xl mx-auto mb-2">
            <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-2">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span class="text-red-800 font-medium">Proveedor en Litigio</span>
                </div>
            </div>
        </div>
    @endif

    <div class="w-full max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Datos Generales -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                        </div>
                    </div>

                    <div class="space-y-3 px-6 py-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Razón Social:</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium">{{ $proveedor->razonsocial }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Nombre Fantasía:</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $proveedor->fantasia ?? '-' }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">CUIT:</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $proveedor->cuit }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Estado:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $proveedor->estado->estado }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Correo:</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $proveedor->correo }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Teléfono:</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $proveedor->telefono ?? '-' }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Horario:</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $proveedor->horario ?? '-' }}</div>
                        </div>

                        @if($proveedor->webpage)
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Sitio Web:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                <a href="{{ $proveedor->webpage }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $proveedor->webpage }}
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Cargado:</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $proveedor->created_at->format('d-m-Y') }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">ID:</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $proveedor->id }}</div>
                        </div>
                    </div>
                </div>

                <!-- Documentación -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Documentación</h3>
                        </div>
                        @can('Proveedores/Documentos/Crear')
                            @livewire('proveedores.proveedors.show.cargar-documento', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))
                        @endcan
                    </div>

                    <div class="px-6 py-4">
                        @if (count($proveedor->documentos) == 0)
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay documentos</h3>
                                <p class="text-gray-500">Los documentos del proveedor aparecerán aquí.</p>
                            </div>
                        @else
                            @php $td_id = 0; @endphp
                            @foreach ($proveedor->documentos as $documento)
                                @if ($documento->documentoTipo->id != $td_id)
                                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $documento->documentoTipo->codigo }} - {{ $documento->documentoTipo->nombre }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Vencimiento: {{ $documento->vencimiento ? $documento->vencimiento->format('d-m-Y') : 'Sin especificar' }}
                                                    @if ($documento->vencimiento)
                                                        @if ($documento->vencimiento->isPast())
                                                            <span class="text-red-500 ml-1" title="Documentación Vencida">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 inline">
                                                                    <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        @elseif ($documento->vencimiento->subDays(30)->isPast())
                                                            <span class="text-yellow-500 ml-1" title="Documentación a Vencer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 inline">
                                                                    <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @livewire('proveedores.proveedors.show.ver-documentacion-modal', ['proveedor' => $proveedor, 'documentoTipo' => $documento->documentoTipo], key($proveedor->id . microtime(true)))
                                            <a href="{{ route('proveedores.documentos.show', $documento) }}"
                                               target="_blank" 
                                               class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Descargar
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @php $td_id = $documento->documentoTipo->id; @endphp
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Representantes Legales -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Representantes Legales</h3>
                        </div>
                        @can('Proveedores/Proveedores/Editar')
                            @livewire('proveedores.proveedors.show.cargar-apoderados', ['proveedor' => $proveedor, 'contexto' => 'representante'], key($proveedor->id . 'representantes' . microtime(true)))
                        @endcan
                    </div>

                    <div class="px-6 py-4">
                        @php $representantes = $proveedor->apoderados->where('tipo', 'representante')->sortByDesc('activo'); @endphp
                        @forelse ($representantes as $apoderado)
                            <div class="mb-6 last:mb-0">
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-8 w-8 {{ $apoderado->activo ? 'text-purple-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium {{ $apoderado->activo ? 'text-gray-900' : 'text-gray-500' }}">{{ $apoderado->nombre }}</div>
                                            <div class="text-xs text-gray-500">
                                                Representante Legal - {{ $apoderado->activo ? 'Activo' : 'Histórico' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @can('Proveedores/Proveedores/Editar')
                                            @livewire('proveedores.proveedors.show.editar-apoderados', ['apoderado' => $apoderado], key($apoderado->id.microtime(true)))
                                        @endcan
                                    </div>
                                </div>
                                
                                <!-- Documentos del representante legal -->
                                <div class="ml-11 mt-3">
                                    @forelse ($apoderado->documentos->sortByDesc('created_at') as $documento)
                                        @if (!$documento->validacion->validado)
                                            @continue
                                        @endif
                                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-b-0">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div class="text-xs text-gray-600">
                                                    <a href="{{ route('proveedores.documentos.show', $documento) }}" 
                                                       class="text-blue-600 hover:underline" target="_blank">
                                                        Descargar
                                                    </a>
                                                    @if (!$loop->first)
                                                        <span class="text-gray-400 ml-1">(archivo viejo)</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="text-xs text-gray-500">
                                                    Vencimiento: {{ $documento->vencimiento ? $documento->vencimiento->format('d-m-Y') : 'Sin especificar'}}
                                                </div>
                                                @can('Proveedores/Proveedores/Editar')
                                                    @livewire('proveedores.proveedors.show.borrar-documento-apoderado', ['documento' => $documento], key($documento->id.microtime(true)))
                                                @endcan
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-xs text-gray-500 italic">No hay documentos cargados</div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay representantes legales</h3>
                                <p class="text-gray-500">Los representantes legales aparecerán aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Apoderados -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Apoderados</h3>
                        </div>
                        @can('Proveedores/Proveedores/Editar')
                            @livewire('proveedores.proveedors.show.cargar-apoderados', ['proveedor' => $proveedor, 'contexto' => 'apoderado'], key($proveedor->id . 'apoderados' . microtime(true)))
                        @endcan
                    </div>

                    <div class="px-6 py-4">
                        @php $apoderados = $proveedor->apoderados->where('tipo', 'apoderado')->sortByDesc('activo'); @endphp
                        @forelse ($apoderados as $apoderado)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 {{ $apoderado->activo ? 'text-indigo-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                                                            <div>
                                            <div class="text-sm font-medium {{ $apoderado->activo ? 'text-gray-900' : 'text-gray-500' }}">
                                                {{ $apoderado->nombre ?? 'Apoderado' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Apoderado - {{ $apoderado->activo ? 'Activo' : 'Histórico' }}
                                            </div>
                                        </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @can('Proveedores/Proveedores/Editar')
                                        @livewire('proveedores.proveedors.show.editar-apoderados', ['apoderado' => $apoderado], key($apoderado->id.microtime(true)))
                                        @livewire('proveedores.proveedors.show.borrar-apoderado', ['apoderado' => $apoderado], key($apoderado->id.microtime(true)))
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay apoderados</h3>
                                <p class="text-gray-500">Los apoderados aparecerán aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Contactos -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2v-6a2 2 0 012-2zM7 8H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v-6a2 2 0 00-2-2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Contactos</h3>
                        </div>
                        @can('Proveedores/Proveedores/Editar')
                            @livewire('proveedores.proveedors.show.cargar-contacto', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))
                        @endcan
                    </div>

                    <div class="px-6 py-4">
                        @forelse ($proveedor->contactos as $contacto)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $contacto->nombre }}</div>
                                        <div class="text-xs text-gray-500">{{ $contacto->telefono }}</div>
                                        <div class="text-xs text-gray-500">{{ $contacto->correo }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @can('Proveedores/Proveedores/Editar')
                                        @livewire('proveedores.proveedors.show.borrar-contacto', ['contacto' => $contacto], key($contacto->id.microtime(true)))
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2v-6a2 2 0 012-2zM7 8H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v-6a2 2 0 00-2-2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay contactos</h3>
                                <p class="text-gray-500">Los contactos del proveedor aparecerán aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Direcciones -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Direcciones</h3>
                        </div>
                        @can('Proveedores/Proveedores/Editar')
                            @livewire('proveedores.proveedors.show.cargar-direccion', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))
                        @endcan
                    </div>

                    <div class="px-6 py-4">
                        @forelse ($proveedor->direcciones as $direccion)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $direccion->tipo }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $direccion->calle }} #{{ $direccion->altura }}, {{ $direccion->piso }}, {{ $direccion->departamento }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $direccion->ciudad }} ({{ $direccion->codigopostal }}), {{ $direccion->provincia }}, {{ $direccion->pais }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @can('Proveedores/Proveedores/Editar')
                                        @livewire('proveedores.proveedors.show.borrar-direccion', ['direccion' => $direccion], key($direccion->id.microtime(true)))
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay direcciones</h3>
                                <p class="text-gray-500">Las direcciones del proveedor aparecerán aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Rubros -->
                @livewire('proveedores.proveedors.show.rubros-edit', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))

                <!-- Concursos Invitados -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Concursos Invitados</h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $proveedor->invitaciones->count() }} concursos
                            </span>
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        @forelse ($proveedor->invitaciones->sortByDesc('created_at') as $invitacion)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('concursos.concursos.show', $invitacion->concurso) }}" class="hover:text-blue-600">
                                                {{$invitacion->concurso->nombre}} - #{{$invitacion->concurso->numero}}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @switch($invitacion->intencion)
                                                @case(0)
                                                    <span class="text-yellow-500">Nunca contestó</span>
                                                    @break
                                                @case(1)
                                                    <span class="text-blue-500">Hubo intención</span>
                                                    @break
                                                @case(2)
                                                    <span class="text-red-500">Hubo negativa</span>
                                                    @break
                                                @case(3)
                                                    <span class="text-green-500">Presentó Oferta</span>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay concursos</h3>
                                <p class="text-gray-500">Los concursos invitados aparecerán aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
