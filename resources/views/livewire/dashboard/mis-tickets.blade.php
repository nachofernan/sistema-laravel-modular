<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Mis Tickets</h3>
            <div class="flex space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $ticketsAbiertos->count() }} abiertos
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $ticketsCerrados->count() }} cerrados
                </span>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="space-y-3">
            
            <!-- Tickets Abiertos -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="flex-shrink-0">
                        <div class="h-2 w-2 bg-green-400 rounded-full"></div>
                    </div>
                    <h4 class="ml-3 text-sm font-semibold text-gray-900 uppercase tracking-wider">
                        Tickets Abiertos
                    </h4>
                </div>
                
                <div class="space-y-3">
                    @forelse($ticketsAbiertos as $ticket)
                    <div wire:click="abrirTicket({{ $ticket->id }})" 
                         class="border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-blue-300 transition-all cursor-pointer group">
                        
                        <!-- Header del ticket -->
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-600 text-white">
                                    #{{ $ticket->codigo }}
                                </span>
                                @if($ticket->mensajesNuevos())
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                                    <svg class="h-1.5 w-1.5 mr-1 fill-current text-red-400" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Nuevo mensaje
                                </span>
                                @endif
                            </div>
                            <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        
                        <!-- Contenido del ticket -->
                        <div class="text-sm">
                            <div class="font-medium text-gray-900 mb-1">{{ $ticket->categoria->nombre }}</div>
                            <div class="text-gray-500 text-xs">
                                Creado {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <p class="text-sm text-gray-500">No tienes tickets abiertos</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Tickets Cerrados -->
            <div>
                <div class="flex items-center mb-2">
                    <div class="flex-shrink-0">
                        <div class="h-2 w-2 bg-gray-400 rounded-full"></div>
                    </div>
                    <h4 class="ml-3 text-sm font-semibold text-gray-900 uppercase tracking-wider">
                        Tickets Cerrados
                    </h4>
                </div>
                
                <div class="space-y-3">
                    @forelse($ticketsCerrados as $ticket)
                    <div wire:click="abrirTicket({{ $ticket->id }})" 
                         class="border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-gray-400 transition-all cursor-pointer group">
                        
                        <!-- Header del ticket -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-500 text-white">
                                #{{ $ticket->codigo }}
                            </span>
                            <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        
                        <!-- Contenido del ticket -->
                        <div class="text-sm">
                            <div class="font-medium text-gray-700 mb-1">{{ $ticket->categoria->nombre }}</div>
                            <div class="text-gray-500 text-xs">
                                Cerrado {{ \Carbon\Carbon::parse($ticket->finalizado)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-gray-500">No tienes tickets cerrados</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>