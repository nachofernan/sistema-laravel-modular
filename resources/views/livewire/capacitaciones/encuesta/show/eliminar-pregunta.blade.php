<div>
    <!-- Botón para abrir modal -->
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
    </button>

    <!-- Modal -->
    <div x-data="{ open: @entangle('open') }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Modal panel -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md"
                 x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="open = false">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Eliminar Pregunta
                            </h3>
                        </div>
                        <button @click="open = false" 
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <!-- Icono de advertencia -->
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>

                    <!-- Contenido de la pregunta -->
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            ¿Estás seguro de eliminar esta pregunta?
                        </h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-left">
                            <div class="text-sm font-medium text-gray-900 mb-2">
                                Pregunta a eliminar:
                            </div>
                            <div class="text-sm text-gray-700">
                                "{{ Str::limit($pregunta->pregunta, 100) }}"
                            </div>
                            
                            @if($pregunta->con_opciones)
                                <div class="mt-3">
                                    <div class="text-xs text-gray-500 mb-1">Opciones disponibles:</div>
                                    <div class="space-y-1">
                                        @foreach($pregunta->opciones->take(3) as $opcion)
                                        <div class="text-xs text-gray-600">• {{ $opcion->opcion }}</div>
                                        @endforeach
                                        @if($pregunta->opciones->count() > 3)
                                        <div class="text-xs text-gray-500 italic">y {{ $pregunta->opciones->count() - 3 }} opciones más...</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Verificación de respuestas existentes -->
                    @php
                        $tieneRespuestas = false;
                        $totalRespuestas = 0;
                        
                        if($pregunta->con_opciones) {
                            $totalRespuestas = $pregunta->opciones->sum(function($opcion) {
                                return \App\Models\Capacitaciones\Respuesta::where('opcion_id', $opcion->id)->count();
                            });
                        } else {
                            $totalRespuestas = \App\Models\Capacitaciones\Respuesta::where('pregunta_id', $pregunta->id)->count();
                        }
                        
                        $tieneRespuestas = $totalRespuestas > 0;
                    @endphp

                    @if($tieneRespuestas)
                        <!-- Advertencia de respuestas existentes -->
                        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">¡Atención!</h3>
                                    <div class="mt-1 text-sm text-red-700">
                                        <p class="mb-2">
                                            Esta pregunta tiene <strong>{{ $totalRespuestas }}</strong> 
                                            {{ $totalRespuestas == 1 ? 'respuesta' : 'respuestas' }} de usuarios.
                                        </p>
                                        <p class="text-xs">
                                            Al eliminarla, se perderán permanentemente todas las respuestas asociadas.
                                            Esta acción no se puede deshacer.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmación adicional para preguntas con respuestas -->
                        <div class="mb-6">
                            <label class="flex items-center p-3 border border-red-200 rounded-md bg-red-50">
                                <input type="checkbox" 
                                       x-model="confirmarEliminacion"
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm text-red-700">
                                    Entiendo que se eliminarán todas las respuestas y esta acción es irreversible
                                </span>
                            </label>
                        </div>
                    @else
                        <!-- Información para preguntas sin respuestas -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Información</h3>
                                    <div class="mt-1 text-sm text-blue-700">
                                        Esta pregunta no tiene respuestas aún, por lo que se puede eliminar sin pérdida de datos.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Consecuencias de la eliminación -->
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Al eliminar esta pregunta:</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Se eliminará permanentemente de la encuesta
                            </li>
                            @if($pregunta->con_opciones)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Se eliminarán todas sus opciones ({{ $pregunta->opciones->count() }})
                            </li>
                            @endif
                            @if($tieneRespuestas)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Se perderán todas las respuestas ({{ $totalRespuestas }})
                            </li>
                            @endif
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                No se puede deshacer esta acción
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
                    <button type="button" 
                            @click="open = false"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors">
                        Cancelar
                    </button>
                    
                    <form action="{{ route('capacitaciones.preguntas.destroy', $pregunta) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                @if($tieneRespuestas) 
                                    x-bind:disabled="!confirmarEliminacion"
                                    x-bind:class="confirmarEliminacion ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                @else
                                    class="bg-red-600 hover:bg-red-700 text-white"
                                @endif
                                class="px-4 py-2 font-medium rounded-md transition-colors"
                                onclick="return confirm('¿Estás completamente seguro? Esta acción no se puede deshacer.')">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar Pregunta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('eliminarPregunta', () => ({
                confirmarEliminacion: false
            }))
        })
    </script>
    
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
