<div>
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors
                   {{ $color == 'blue' ? 'bg-blue-500 hover:bg-blue-600 text-white' : 
                      ($color == 'green' ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-yellow-500 hover:bg-yellow-600 text-white') }}">
        @if($color == 'blue')
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        @elseif($color == 'green')
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        @else
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        @endif
        {{ $texto_boton }}
    </button>

    @can('Inventario/Elementos/Editar')
        <x-dialog-modal wire:model="open" maxWidth="md">
            <x-slot name="title">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-base font-medium text-gray-900">Gestión de Entrega</span>
                </div>
            </x-slot>

            <x-slot name="content">
                <div class="space-y-4">
                    <!-- Información del elemento -->
                    <div class="bg-gray-50 rounded-md p-4">
                        <div class="flex items-center">
                            @if ($elemento->categoria->icono)
                                <div class="mr-3">
                                    {!! $elemento->categoria->icono !!}
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $elemento->codigo }}</div>
                                <div class="text-xs text-gray-500">{{ $elemento->categoria->nombre }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción de la acción -->
                    <div class="text-sm text-gray-700">
                        {{ $texto_descripcion }}
                    </div>

                    <!-- Estado actual -->
                    <div class="bg-blue-50 rounded-md p-3">
                        <div class="text-xs text-blue-700">
                            <strong>Estado actual:</strong>
                            @if (!$elemento->entregaActual())
                                Sin entrega registrada
                            @elseif($elemento->entregaActual()->fecha_firma)
                                Elemento entregado y firmado
                            @else
                                Entrega registrada, esperando firma
                            @endif
                        </div>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex justify-center space-x-3 w-full">
                    @if (!$elemento->entregaActual())
                        <button wire:click="activar(1)" 
                                class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Solicitar Firma
                        </button>
                    @else
                        @if ($elemento->entregaActual()->fecha_firma)
                            <button wire:click="activar(2)" 
                                    class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                Registrar Devolución
                            </button>
                        @else
                            <button wire:click="activar(2)" 
                                    class="px-6 py-3 bg-lime-500 hover:bg-lime-600 text-white font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Marcar como Entregado
                            </button>
                            <button wire:click="activar(3)" 
                                    class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar Entrega
                            </button>
                        @endif
                    @endif
                </div>
            </x-slot>
        </x-dialog-modal>
    @endcan
</div>