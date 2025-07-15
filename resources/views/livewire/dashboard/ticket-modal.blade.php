<div>
    @if($showModal && $ticket)
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             wire:click="cerrarModal">
            
            <!-- Modal Container -->
            <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white"
                 wire:click.stop>
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xl font-semibold text-gray-900">
                                Ticket #{{ $ticket->codigo }}
                            </h3>
                            <p class="text-sm text-gray-600">{{ $ticket->categoria->nombre }}</p>
                        </div>
                    </div>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    
                    <!-- Datos del Ticket -->
                    <div class="lg:col-span-2">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Información del Ticket
                            </h4>
                            
                            <div class="space-y-3">
                                <!-- Código -->
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Código:</span>
                                    <span class="text-sm text-gray-900 font-medium">#{{ $ticket->codigo }}</span>
                                </div>
                                
                                <!-- Estado -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Estado:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $ticket->finalizado ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $ticket->estado->nombre ?? ($ticket->finalizado ? 'Cerrado' : 'Abierto') }}
                                    </span>
                                </div>
                                
                                <!-- Documento -->
                                @if($ticket->documento)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Documento:</span>
                                    <a href="{{ route('home.tickets.documentos', $ticket) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Descargar
                                    </a>
                                </div>
                                @endif
                                
                                <!-- Fechas -->
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Creación:</span>
                                    <span class="text-sm text-gray-900">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                @if($ticket->finalizado)
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Finalizado:</span>
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($ticket->finalizado)->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Notas Iniciales -->
                            @if($ticket->notas)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h5 class="text-sm font-medium text-gray-900 mb-2">Descripción inicial:</h5>
                                <div class="text-sm text-gray-700 bg-white p-3 rounded border">
                                    {!! nl2br(e($ticket->notas)) !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Mensajes -->
                    <div class="lg:col-span-3">
                        <div class="flex flex-col h-96">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Conversación
                                </h4>
                                <span class="text-sm text-gray-500">
                                    {{ $ticket->mensajes->count() }} mensajes
                                </span>
                            </div>
                            
                            <!-- Lista de Mensajes -->
                            <div class="flex-1 overflow-y-auto bg-gray-50 rounded-lg p-4 space-y-3">
                                @forelse($ticket->mensajes->reverse() as $mensaje)
                                <div class="flex {{ $mensaje->user_id === $ticket->user_id ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $mensaje->user_id === $ticket->user_id ? 'bg-blue-600 text-white' : 'bg-white text-gray-800 border border-gray-200' }}">
                                        <div class="text-xs opacity-75 mb-1">
                                            <span class="font-medium">
                                                {{ $mensaje->user_id === $ticket->user_id ? $mensaje->user->realname : 'Sistemas' }}
                                            </span>
                                            <span class="ml-2">{{ $mensaje->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="text-sm">{{ $mensaje->mensaje }}</div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-gray-500 py-8">
                                    <svg class="h-12 w-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <p class="text-sm">No hay mensajes en esta conversación</p>
                                </div>
                                @endforelse
                            </div>
                            
                            <!-- Formulario de Nuevo Mensaje -->
                            @if(!$ticket->finalizado)
                            <div class="mt-4">
                                <form wire:submit="enviarMensaje" class="flex gap-2">
                                    <div class="flex-1">
                                        <input wire:model="nuevoMensaje" 
                                               type="text" 
                                               placeholder="Escribe tu mensaje..." 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               maxlength="500">
                                        @error('nuevoMensaje') 
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                                        @enderror
                                    </div>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove>Enviar</span>
                                        <span wire:loading>Enviando...</span>
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                <p class="text-sm text-yellow-800">Este ticket está cerrado. No se pueden enviar más mensajes.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>