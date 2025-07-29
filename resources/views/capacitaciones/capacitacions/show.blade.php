<x-app-layout>
    <x-page-header title="{{ $capacitacion->nombre }}">
        <x-slot:actions>
            @can('Capacitaciones/Capacitaciones/Editar')
                <a href="{{ route('capacitaciones.capacitacions.edit', $capacitacion) }}" 
                   class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    Editar Capacitación
                </a>
            @endcan
            <a href="{{ route('capacitaciones.capacitacions.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Datos Generales -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Nombre:</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium">{{ $capacitacion->nombre }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Fecha:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ Carbon\Carbon::create($capacitacion->fecha)->format('d-m-Y') }}
                                </span>
                            </div>
                        </div>

                        @if($capacitacion->descripcion)
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Descripción:</div>
                            <div class="col-span-2 text-sm text-gray-700 bg-gray-50 p-3 rounded-md">
                                {!! nl2br($capacitacion->descripcion) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Documentos -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Documentos</h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $capacitacion->documentos->count() }} documentos
                            </span>
                            @can('Capacitaciones/Capacitaciones/Editar')
                                @livewire('capacitaciones.capacitacions.show.agregar-documento', ['capacitacion' => $capacitacion], key($capacitacion->id . microtime(true)))
                            @endcan
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        @forelse ($capacitacion->documentos as $documento)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $documento->nombre }}</div>
                                        <div class="text-xs text-gray-500">{{ $documento->archivo }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ Storage::disk('capacitaciones')->url($documento->file_storage) }}"
                                       target="_blank" 
                                       class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Descargar
                                    </a>
                                    @can('Capacitaciones/Capacitaciones/Editar')
                                    <form action="{{ route('capacitaciones.documentos.destroy', $documento) }}" method="POST" class="inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" 
                                                class="inline-flex items-center px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors"
                                                onclick="return confirm('¿Estás seguro de eliminar este documento?')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay documentos</h3>
                                <p class="text-gray-500">Los documentos de la capacitación aparecerán aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Encuestas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Encuestas</h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $capacitacion->encuestas->count() }} encuestas
                            </span>
                            @can('Capacitaciones/Encuestas/Crear')
                                @livewire('capacitaciones.capacitacions.show.agregar-encuesta', ['capacitacion' => $capacitacion], key($capacitacion->id . microtime(true)))
                            @endcan
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        @forelse ($capacitacion->encuestas as $encuesta)
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            @can('Capacitaciones/Encuestas/Ver')
                                                <a href="{{ route('capacitaciones.encuestas.show', $encuesta) }}" class="hover:text-blue-600">
                                                    {{ $encuesta->nombre }}
                                                </a>
                                            @else
                                                {{ $encuesta->nombre }}
                                            @endcan
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $encuesta->descripcion }}</div>
                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                         {{ $encuesta->estado == 0 ? 'bg-gray-100 text-gray-800' : 
                                                            ($encuesta->estado == 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                                {{ ucfirst($encuesta->estado()['nombre']) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-500">{{ $encuesta->preguntas->count() }} preguntas</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay encuestas</h3>
                                <p class="text-gray-500">Las encuestas de la capacitación aparecerán aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Panel de Invitaciones -->
            <div class="lg:col-span-5">
                @livewire('capacitaciones.capacitacions.show.invitaciones', ['capacitacion' => $capacitacion], key($capacitacion->id . microtime(true)))
            </div>
        </div>
    </div>
</x-app-layout>