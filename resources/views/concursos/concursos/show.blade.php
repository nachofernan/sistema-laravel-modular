<x-app-layout>
    <x-page-header>
        <x-slot:title>{{ $concurso->nombre }} <span class="text-sm font-normal text-gray-500 ml-2">#{{ $concurso->numero ?? 'Sin Número' }}</span></x-slot:title>
        <x-slot:actions>
            <a href="{{route('concursos.pdfbb', $concurso)}}" 
               class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm rounded-md transition-colors inline-flex items-center"
               target="_blank"
               aria-label="Descargar PDF del concurso"
               title="Descargar PDF del concurso">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1">
                    <path fill-rule="evenodd" d="M4 2a1.5 1.5 0 0 0-1.5 1.5v9A1.5 1.5 0 0 0 4 14h8a1.5 1.5 0 0 0 1.5-1.5V6.621a1.5 1.5 0 0 0-.44-1.06L9.94 2.439A1.5 1.5 0 0 0 8.878 2H4Zm4 3.5a.75.75 0 0 1 .75.75v2.69l.72-.72a.75.75 0 1 1 1.06 1.06l-2 2a.75.75 0 0 1-1.06 0l-2-2a.75.75 0 0 1 1.06-1.06l.72.72V6.25A.75.75 0 0 1 8 5.5Z" clip-rule="evenodd" />
                </svg>
                Descargar PDF
            </a>
            @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                @if ($concurso->estado->id < 4)
                    @livewire('concursos.concurso.show.acciones-concurso', ['concurso' => $concurso], key($concurso->id))
                @endif
            @endif
        </x-slot:actions>
    </x-page-header>

    <!-- Estado del Concurso -->
    <div class="w-full max-w-7xl mx-auto mb-2">
        <div class="rounded-lg shadow-sm border-2 border-gray-200 p-2
        @switch($concurso->estado->id)
            @case(1)
                @if ($concurso->fecha_cierre > now())
                    bg-gradient-to-r from-orange-800 to-orange-500
                @else
                    bg-gradient-to-r from-gray-800 to-gray-500
                @endif
                @break
            @case(2)
                @if ($concurso->fecha_cierre > now())
                    bg-gradient-to-r from-green-800 to-green-500
                @else
                    bg-gradient-to-r from-yellow-800 to-yellow-500
                @endif
                @break
            @case(3)
                bg-gradient-to-r from-blue-800 to-blue-500
                @break
            @case(4)
                bg-gradient-to-r from-blue-800 to-blue-500
                @break
            @case(5)
                bg-gradient-to-r from-red-800 to-red-500
                @break
        @endswitch
        ">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full font-bold text-white tracking-wider">
                            @switch($concurso->estado->id)
                                @case(1)
                                    @if ($concurso->fecha_cierre > now())
                                        Pre-Carga
                                    @else
                                        Vencido
                                    @endif
                                    @break
                                @case(2)
                                    @if ($concurso->fecha_cierre > now())
                                        Activo
                                    @else
                                        Cerrado
                                    @endif
                                    @break
                                @default
                                    {{ $concurso->estado->nombre }}
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                            @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                                @livewire('concursos.concurso.show.edit-concurso', ['concurso' => $concurso], key($concurso->id))
                            @endif
                        @endif
                    </div>

                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Nombre:</div>
                                <div class="col-span-2 text-sm text-gray-900">{{ $concurso->nombre }}</div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Número:</div>
                                <div class="col-span-2 text-sm text-gray-900">#{{ $concurso->numero ?? 'Sin Número' }}</div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Descripción:</div>
                                <div class="col-span-2 text-sm text-gray-900">{{ $concurso->descripcion }}</div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Legajo Electrónico:</div>
                                <div class="col-span-2 text-sm text-gray-900">
                                    <a href="{{$concurso->legajo}}" 
                                       target="_blank" 
                                       title="Ver Legajo"
                                       class="text-blue-600 hover:underline flex items-center transition-colors duration-200"
                                       aria-label="Ver legajo electrónico">
                                       {{$concurso->numero_legajo}}
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 ml-1 pb-1">
                                        <path d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z" />
                                        <path d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z" />
                                      </svg>                                              
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Usuario Gestor:</div>
                                <div class="col-span-2 text-sm text-gray-900">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Sedes:</div>
                                <div class="col-span-2 text-sm text-gray-900">
                                    @foreach ($concurso->sedes as $sede)
                                        <div>{{ $sede->nombre }}</div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Fecha Inicio:</div>
                                <div class="col-span-2 text-sm text-gray-900">{{ $concurso->fecha_inicio->format('d-m-Y - H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Fecha de Cierre Prominente -->
                    <div class="bg-gray-50 px-6 py-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-700">Fecha de Cierre:</span>
                            <span class="text-xl font-bold text-gray-900">{{ $concurso->fecha_cierre->format('d-m-Y - H:i') }}</span>
                            @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                @if ($concurso->estado->id == 2 && $concurso->fecha_cierre > now())
                                    @livewire('concursos.concurso.show.create-prorroga', ['concurso' => $concurso], key($concurso->id))
                                @endif
                            @endif
                        </div>

                        @if (count($concurso->prorrogas) > 0)
                            <div class="mt-6 space-y-2 bg-white rounded-md shadow-sm py-2 text-xs">
                                @foreach ($concurso->prorrogas as $key => $prorroga)
                                    <div class="flex justify-between items-center px-4">
                                        <span class="font-medium">Prórroga {{ $key+1 }}</span>
                                        <div class="text-xs text-gray-600">
                                            {{ $prorroga->fecha_anterior->format('d-m-Y - H:i') }} 
                                            <span class="mx-2">➔</span> 
                                            {{ $prorroga->fecha_actual->format('d-m-Y - H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historial de Estados -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Historial de Estados</h3>
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @foreach ($concurso->historial as $historial)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 
                                            @switch($historial->estado->id)
                                                @case(1)
                                                    text-orange-500
                                                    @break
                                                @case(2)
                                                    @if ($concurso->fecha_cierre > now())
                                                        text-green-500
                                                    @else
                                                        text-yellow-500
                                                    @endif
                                                    @break
                                                @case(3)
                                                    text-blue-500
                                                    @break
                                                @case(4)
                                                    text-blue-500
                                                    @break
                                                @case(5)
                                                    text-red-500
                                                    @break
                                                @default
                                                    text-purple-500
                                            @endswitch" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $historial->estado->nombre }}</div>
                                            <div class="text-xs text-gray-500">{{ $historial->created_at->format('d-m-Y - H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if ($historial->estado->id == 2 && $concurso->fecha_cierre < now())
                                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Cerrado por Fecha</div>
                                                <div class="text-xs text-gray-500">{{ $concurso->fecha_cierre->format('d-m-Y - H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Administrar Contactos -->
                @livewire('concursos.concurso.show.administrar-contactos', ['concurso' => $concurso])

                <!-- Documentación Adjunta -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Documentación Adjunta del Concurso</h3>
                        </div>
                        @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                            @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                                @livewire('concursos.concurso.show.create-documento', ['concurso' => $concurso], key($concurso->id))
                            @endif
                        @endif
                    </div>

                    <div class="px-6 py-4">
                        @forelse ($concurso->documentos as $documento)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{$documento->documentoTipo->nombre}}</div>
                                        <div class="text-xs text-gray-500">{{$documento->documentoTipo->codigo}}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a 
                                        href="{{ route('concursos.documentos.show', $documento->id) }}" 
                                        target="_blank"
                                        class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors"
                                        aria-label="Descargar documento {{$documento->documentoTipo->nombre}}"
                                        title="Descargar documento"
                                    >                                            
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 mr-1">
                                            <path fill-rule="evenodd" d="M4 2a1.5 1.5 0 0 0-1.5 1.5v9A1.5 1.5 0 0 0 4 14h8a1.5 1.5 0 0 0 1.5-1.5V6.621a1.5 1.5 0 0 0-.44-1.06L9.94 2.439A1.5 1.5 0 0 0 8.878 2H4Zm4 3.5a.75.75 0 0 1 .75.75v2.69l.72-.72a.75.75 0 1 1 1.06 1.06l-2 2a.75.75 0 0 1-1.06 0l-2-2a.75.75 0 0 1 1.06-1.06l.72.72V6.25A.75.75 0 0 1 8 5.5Z" clip-rule="evenodd" />
                                        </svg>
                                        Descargar
                                    </a>
                                    @if ($concurso->estado->id == 1 && $concurso->fecha_cierre > now() && (auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id()))
                                        <form action="{{ route('concursos.documentos.destroy', $documento->id) }}" method="post" class="inline">
                                            @csrf
                                            @method('delete')
                                            <button 
                                                type="submit" 
                                                onclick="return confirm('¿Está seguro que desea eliminar el documento?')"
                                                class="inline-flex items-center px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors"
                                                aria-label="Eliminar documento {{$documento->documentoTipo->nombre}}"
                                                title="Eliminar documento"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 mr-1">
                                                    <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5H14v1.5H2.75a.75.75 0 0 0 0 1.5H5v.75A2.75 2.75 0 0 0 7.75 11h.5A2.75 2.75 0 0 0 11 8.25v-.75h2.25a.75.75 0 0 0 0-1.5H11v-1.5h2.25a.75.75 0 0 0 0-1.5H11v-.75A2.75 2.75 0 0 0 8.25 2h-.5A2.75 2.75 0 0 0 5 4.75ZM8.25 3.5a1.25 1.25 0 0 1 1.25 1.25v4.5a1.25 1.25 0 0 1-1.25 1.25h-.5A1.25 1.25 0 0 1 6.5 9.25v-4.5A1.25 1.25 0 0 1 7.75 3.5h.5Z" clip-rule="evenodd" />
                                                </svg>
                                                Eliminar
                                            </button>    
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Sin documentación</h3>
                                <p class="text-gray-500">Los documentos del concurso aparecerán aquí.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Documentos Requeridos -->
                @livewire('concursos.concurso.show.require-documentos', ['concurso' => $concurso], key($concurso->id))
            </div>

            <!-- Panel Lateral -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Rubro y Subrubro -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Rubro y Subrubro</h3>
                        </div>
                        @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                            @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                                @livewire('concursos.concurso.rubros', ['concurso' => $concurso], key($concurso->id))
                            @endif
                        @endif
                    </div>

                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Rubro</div>
                                        <div class="text-xs text-gray-500">{{ $concurso->subrubro ? $concurso->subrubro->rubro->nombre : 'Sin Rubro' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Subrubro</div>
                                        <div class="text-xs text-gray-500">{{ $concurso->subrubro ? $concurso->subrubro->nombre : 'Sin Subrubro' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cargar Documentación Post-Apertura -->
                @if ($concurso->estado->id == 3)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Cargar Documentación Post-Apertura</h3>
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        @if ($concurso->permite_carga)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Habilitado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Deshabilitado
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                <div class="flex items-center space-x-2">
                                    @if ($concurso->permite_carga)
                                        <form action="{{ route('concursos.concursos.update', $concurso) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="permite_carga" value="0">
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Deshabilitar</button>
                                        </form>
                                    @else
                                        <form action="{{ route('concursos.concursos.update', $concurso) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="permite_carga" value="1">
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Habilitar</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Invitar Proveedor -->
                @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                    @livewire('concursos.concurso.invitar-proveedor', ['concurso' => $concurso], key($concurso->id))
                @else
                    <!-- Proveedores con Ofertas Presentadas -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900">Proveedores con Ofertas Presentadas</h3>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $concurso->invitaciones->where('intencion', 3)->count() }} proveedores
                            </span>
                        </div>

                        <div class="px-6 py-4">
                            @forelse ($concurso->invitaciones->where('intencion', 3) as $invitacion)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('proveedores.proveedors.show', $invitacion->proveedor) }}"
                                                    class="hover:text-blue-600">
                                                    {{ $invitacion->proveedor->razonsocial }}
                                                </a>
                                                <span class="text-gray-500 text-xs">{{ $invitacion->proveedor->cuit }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <a href="mailto:{{ $invitacion->proveedor->correo }}"
                                                    class="hover:text-blue-600">{{ $invitacion->proveedor->correo }}</a>
                                                <br>
                                                <span class="font-medium text-red-600">
                                                    {{ $invitacion->proveedor->estado->estado }}
                                                </span>
                                                @if ($invitacion->proveedor->falta_a_vencimiento() < 0)
                                                    <span class="ml-2 text-red-600 font-bold text-xs">Doc.
                                                        Vencida</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                            @if ($concurso->estado->id == 3 || $concurso->estado->id == 4)
                                                @livewire('concursos.concurso.show.ver-oferta', ['concurso' => $concurso, 'invitacion' => $invitacion], key($invitacion->id))
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay ofertas presentadas</h3>
                                    <p class="text-gray-500">Los proveedores con ofertas aparecerán aquí.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Otros Proveedores Invitados -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900">Otros Proveedores Invitados</h3>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $concurso->invitaciones->filter(function ($invitacion) { return $invitacion->intencion != 3; })->count() }} proveedores
                            </span>
                        </div>

                        <div class="px-6 py-4">
                            @forelse ($concurso->invitaciones->filter(function ($invitacion) { return $invitacion->intencion != 3; }) as $invitacion)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('proveedores.proveedors.show', $invitacion->proveedor) }}"
                                                    class="hover:text-blue-600">
                                                    {{ $invitacion->proveedor->razonsocial }}
                                                </a>
                                                <span class="text-gray-500 text-xs">{{ $invitacion->proveedor->cuit }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <a href="mailto:{{ $invitacion->proveedor->correo }}"
                                                    class="hover:text-blue-600">{{ $invitacion->proveedor->correo }}</a>
                                                <br>
                                                <span class="font-medium text-red-600">
                                                    {{ $invitacion->proveedor->estado->estado }}
                                                </span>
                                                @if ($invitacion->proveedor->falta_a_vencimiento() < 0)
                                                    <span class="ml-2 text-red-600 font-bold text-xs">Doc.
                                                        Vencida</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @switch($invitacion->intencion)
                                            @case(0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    Nunca contestó
                                                </span>
                                                @break
                                            @case(1)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Hubo intención
                                                </span>
                                                @break
                                            @case(2)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Hubo negativa
                                                </span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay otros proveedores</h3>
                                    <p class="text-gray-500">Los otros proveedores invitados aparecerán aquí.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
