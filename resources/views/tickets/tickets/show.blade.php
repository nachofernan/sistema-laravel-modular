<x-app-layout>
    <x-page-header title="Ticket #{{ $ticket->codigo }}">
        <x-slot:actions>
            @if (!$ticket->finalizado)
                @can('Tickets/Tickets/Editar')
                    @livewire('tickets.tickets.show.edit', ['ticket' => $ticket], key($ticket->id))
                @endcan
            @endif
            <a href="{{ route('tickets.tickets.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-7xl mx-auto">
        <!-- Header del ticket -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">#{{ $ticket->codigo }}</h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>{{ $ticket->categoria->nombre }}</span>
                            <span>•</span>
                            <span>{{ $ticket->user->realname }}</span>
                            <span>•</span>
                            <span>{{ Carbon\Carbon::create($ticket->created_at)->format('d-m-Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                 {{ $ticket->finalizado ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                        {{ $ticket->estado->nombre }}
                    </span>
                    @if($ticket->finalizado)
                        <div class="text-xs text-gray-500 mt-1">
                            Finalizado: {{ Carbon\Carbon::create($ticket->finalizado)->format('d-m-Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información del ticket -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Datos principales -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Información del Ticket</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Código:</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium">#{{ $ticket->codigo }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Solicitante:</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $ticket->user->realname }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Categoría:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $ticket->categoria->nombre }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Estado:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                             {{ $ticket->finalizado ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $ticket->estado->nombre }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Encargado:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                {{ $ticket->encargado->realname ?? 'Sin asignar' }}
                            </div>
                        </div>

                        @if($ticket->documento)
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Documento:</div>
                                <div class="col-span-2 text-sm text-gray-900">
                                    @php $media = $ticket->documento->getFirstMedia('archivos') @endphp
                                    @if($media)
                                        <a href="{{ route('tickets.tickets.documentos', $ticket) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Descargar Documento
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500">Archivo no disponible</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Creado:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                {{ Carbon\Carbon::create($ticket->created_at)->format('d-m-Y H:i') }}
                            </div>
                        </div>

                        @if($ticket->finalizado)
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-sm font-medium text-gray-500">Finalizado:</div>
                                <div class="col-span-2 text-sm text-gray-900">
                                    {{ Carbon\Carbon::create($ticket->finalizado)->format('d-m-Y H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Descripción inicial -->
                @if($ticket->notas)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-4">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Descripción Inicial</h3>
                        </div>
                        <div class="text-sm text-gray-700 bg-gray-50 p-4 rounded-md">
                            {!! nl2br($ticket->notas) !!}
                        </div>
                    </div>
                @endif

                <!-- Estadísticas rápidas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Estadísticas</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total de mensajes:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $ticket->mensajes->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tiempo activo:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $ticket->finalizado ? 
                                    Carbon\Carbon::create($ticket->created_at)->diffInDays(Carbon\Carbon::create($ticket->finalizado)) . ' días' :
                                    Carbon\Carbon::create($ticket->created_at)->diffForHumans() 
                                }}
                            </span>
                        </div>
                        
                        @if($ticket->mensajes->count() > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Última actividad:</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $ticket->mensajes->last()->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mensajes -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Conversación</h3>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $ticket->mensajes->count() }} mensajes
                        </span>
                    </div>

                    <!-- Formulario para nuevo mensaje -->
                    @can('Tickets/Tickets/Mensajes')
                        @if (!$ticket->finalizado)
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <form action="{{ route('tickets.mensajes.store') }}" method="POST" class="flex space-x-3">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                    <div class="flex-1">
                                        <input type="text" 
                                               name="mensaje" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                               placeholder="Escribe tu mensaje..."
                                               required>
                                    </div>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Enviar
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endcan

                    <!-- Lista de mensajes -->
                    <div class="px-6 py-4 max-h-96 overflow-y-auto">
                        @forelse ($ticket->mensajes->reverse() as $mensaje)
                            <div class="flex {{ $mensaje->user_id === $ticket->user_id ? 'justify-end' : 'justify-start' }} mb-4">
                                <div class="max-w-xs lg:max-w-md">
                                    <div class="px-4 py-2 rounded-lg {{ $mensaje->user_id === $ticket->user_id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                                        <div class="text-xs {{ $mensaje->user_id === $ticket->user_id ? 'text-blue-100' : 'text-gray-500' }} mb-1">
                                            <span class="font-medium">
                                                {{ $mensaje->user->realname ?? 'Sistema' }}
                                            </span>
                                            <span class="ml-2">
                                                {{ Carbon\Carbon::create($mensaje->created_at)->format('d-m-Y H:i') }}
                                            </span>
                                            @can('Tickets/Tickets/Mensajes')
                                                @if ($mensaje->esNuevo())
                                                    <span class="ml-2 bg-red-500 text-white text-xs rounded px-1 py-0.5">Nuevo</span>
                                                    @php
                                                        $mensaje->update(['leido' => 1]);
                                                    @endphp
                                                @endif
                                            @endcan
                                        </div>
                                        <div class="text-sm">{{ $mensaje->mensaje }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay mensajes</h3>
                                <p class="text-gray-500">La conversación comenzará cuando se envíe el primer mensaje.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>