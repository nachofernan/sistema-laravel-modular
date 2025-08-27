<div>
    @if($showModal)
        <!-- Modal backdrop -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <!-- Modal content -->
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <!-- Warning icon -->
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    
                    <!-- Title -->
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Confirmar Eliminación
                    </h3>
                    
                    <!-- Message -->
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 mb-4">
                            ¿Estás seguro de que quieres eliminar esta COPRES?
                        </p>
                        
                        <!-- COPRES details -->
                        <div class="bg-gray-50 rounded-lg p-3 text-left">
                            <div class="text-sm text-gray-600">
                                <div class="mb-1">
                                    <span class="font-medium">Fecha:</span> {{ $copres->fecha->format('d/m/Y') }}
                                </div>
                                <div class="mb-1">
                                    <span class="font-medium">Vehículo:</span> {{ $copres->vehiculo->marca }} {{ $copres->vehiculo->modelo }} - {{ $copres->vehiculo->patente }}
                                </div>
                                <div class="mb-1">
                                    <span class="font-medium">Ticket:</span> {{ $copres->numero_ticket_factura }}
                                </div>
                                <div>
                                    <span class="font-medium">Importe:</span> ${{ number_format($copres->importe_final, 2) }}
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-xs text-red-500 mt-3">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex items-center justify-center space-x-3 mt-6">
                        <button wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="confirmDelete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
