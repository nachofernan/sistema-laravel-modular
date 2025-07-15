<div>
    <!-- Botón para abrir modal -->
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
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
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                 x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="open = false">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Editar Pregunta
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

                <!-- Formulario -->
                <form action="{{ route('capacitaciones.preguntas.update', $pregunta) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- Pregunta -->
                        <div>
                            <label for="pregunta" class="block text-sm font-medium text-gray-700 mb-2">
                                Texto de la pregunta *
                            </label>
                            <textarea name="pregunta" 
                                      id="pregunta"
                                      rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      required>{{ $pregunta->pregunta }}</textarea>
                        </div>

                        <!-- Tipo de pregunta (solo informativo si ya tiene respuestas) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de respuesta
                            </label>
                            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 {{ $pregunta->con_opciones ? 'bg-blue-600' : 'bg-gray-300' }} rounded-full mr-2"></div>
                                    <span class="text-sm {{ $pregunta->con_opciones ? 'font-medium text-gray-900' : 'text-gray-500' }}">
                                        {{ $pregunta->con_opciones ? 'Opción múltiple' : 'Texto libre' }}
                                    </span>
                                </div>
                                @if($pregunta->con_opciones && $pregunta->opciones->count() > 0)
                                    <span class="text-xs text-gray-500">
                                        ({{ $pregunta->opciones->count() }} opciones)
                                    </span>
                                @endif
                            </div>
                            
                            @php
                                $tieneRespuestas = false;
                                if($pregunta->con_opciones) {
                                    $tieneRespuestas = $pregunta->opciones->sum(function($opcion) {
                                        return \App\Models\Capacitaciones\Respuesta::where('opcion_id', $opcion->id)->count();
                                    }) > 0;
                                } else {
                                    $tieneRespuestas = \App\Models\Capacitaciones\Respuesta::where('pregunta_id', $pregunta->id)->count() > 0;
                                }
                            @endphp
                            
                            @if($tieneRespuestas)
                                <p class="mt-1 text-xs text-amber-600">
                                    ⚠️ Esta pregunta ya tiene respuestas. No se puede cambiar el tipo.
                                </p>
                            @endif
                        </div>

                        <!-- Estadísticas si tiene respuestas -->
                        @if($tieneRespuestas)
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Estadísticas de respuestas:</h4>
                            @if($pregunta->con_opciones)
                                <div class="space-y-2">
                                    @foreach($pregunta->opciones as $opcion)
                                        @php
                                            $respuestas = \App\Models\Capacitaciones\Respuesta::where('opcion_id', $opcion->id)->count();
                                        @endphp
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-blue-800">{{ Str::limit($opcion->opcion, 30) }}</span>
                                            <span class="font-medium text-blue-900">{{ $respuestas }} {{ $respuestas == 1 ? 'respuesta' : 'respuestas' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                @php
                                    $totalRespuestas = \App\Models\Capacitaciones\Respuesta::where('pregunta_id', $pregunta->id)->count();
                                @endphp
                                <p class="text-sm text-blue-800">
                                    {{ $totalRespuestas }} {{ $totalRespuestas == 1 ? 'respuesta recibida' : 'respuestas recibidas' }}
                                </p>
                            @endif
                        </div>
                        @endif

                        <!-- Opciones de la pregunta (si es de opción múltiple) -->
                        @if($pregunta->con_opciones)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Opciones de respuesta
                            </label>
                            <div class="space-y-2">
                                @foreach($pregunta->opciones as $opcion)
                                    <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-md">
                                        <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex-shrink-0"></div>
                                        <span class="flex-1 text-sm text-gray-700">{{ $opcion->opcion }}</span>
                                        @php
                                            $respuestasOpcion = \App\Models\Capacitaciones\Respuesta::where('opcion_id', $opcion->id)->count();
                                        @endphp
                                        @if($respuestasOpcion > 0)
                                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">
                                                {{ $respuestasOpcion }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Para modificar las opciones, usa los controles en la vista principal de la encuesta.
                            </p>
                        </div>
                        @endif

                        <!-- Información adicional -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Recomendaciones:</h3>
                                    <div class="mt-1 text-sm text-yellow-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @if($tieneRespuestas)
                                                <li>Evita cambios drásticos ya que usuarios han respondido esta pregunta</li>
                                                <li>Considera si los cambios afectan el significado de las respuestas existentes</li>
                                            @else
                                                <li>Puedes hacer todos los cambios necesarios antes de activar la encuesta</li>
                                                <li>Asegúrate de que la pregunta sea clara y sin ambigüedades</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3">
                        <button type="button" 
                                @click="open = false"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
