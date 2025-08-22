<div>
    <!-- Botón para abrir modal -->
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Agregar Pregunta
    </button>

    <!-- Modal -->
    <div x-data="{ 
            open: @entangle('open'),
            tipo_pregunta: 'opcion_multiple',
            pregunta_texto: '',
            opciones: [''],
            agregarOpcion() {
                this.opciones.push('');
            },
            eliminarOpcion(index) {
                if (this.opciones.length > 1) {
                    this.opciones.splice(index, 1);
                }
            }
         }" 
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
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
                 x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="open = false">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Agregar Nueva Pregunta
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
                <form action="{{ route('capacitaciones.preguntas.store') }}" method="POST" 
                      @submit="console.log('Enviando formulario:', { pregunta: pregunta_texto, tipo_pregunta: tipo_pregunta, opciones: opciones })">
                    @csrf
                    <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- Pregunta -->
                        <div>
                            <label for="pregunta" class="block text-sm font-medium text-gray-700 mb-2">
                                Texto de la pregunta *
                            </label>
                            <textarea name="pregunta" 
                                      id="pregunta"
                                      x-model="pregunta_texto"
                                      rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                      placeholder="Escribe aquí tu pregunta..."
                                      required></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                Sé claro y específico para obtener respuestas útiles
                            </p>
                        </div>

                        <!-- Tipo de pregunta -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Tipo de respuesta *
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" 
                                           x-model="tipo_pregunta"
                                           value="opcion_multiple" 
                                           name="tipo_pregunta" 
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Opción múltiple</div>
                                        <div class="text-xs text-gray-500">Los usuarios seleccionan una opción de una lista</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="radio" 
                                           x-model="tipo_pregunta"
                                           value="texto_libre" 
                                           name="tipo_pregunta" 
                                           @change="opciones = ['']"
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">Texto libre</div>
                                        <div class="text-xs text-gray-500">Los usuarios escriben su respuesta libremente</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Opciones de respuesta (solo para opción múltiple) -->
                        <div x-show="tipo_pregunta === 'opcion_multiple'" x-transition>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Opciones de respuesta
                            </label>
                            
                            <div class="space-y-3">
                                <template x-for="(opcion, index) in opciones" :key="index">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1">
                                            <input type="text" 
                                                   :name="tipo_pregunta === 'opcion_multiple' ? 'opciones[]' : ''"
                                                   x-model="opciones[index]"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors" 
                                                   :placeholder="'Opción ' + (index + 1)"
                                                   :required="tipo_pregunta === 'opcion_multiple'">
                                        </div>
                                        <button type="button" 
                                                @click="eliminarOpcion(index)"
                                                x-show="opciones.length > 1"
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            
                            <button type="button" 
                                    @click="agregarOpcion()"
                                    class="mt-3 inline-flex items-center px-3 py-2 bg-orange-100 text-orange-700 text-sm font-medium rounded-md hover:bg-orange-200 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Agregar otra opción
                            </button>
                        </div>

                        <!-- Vista previa -->
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Vista previa:</h4>
                            
                            <div class="bg-white border border-gray-200 rounded-md p-4">
                                <div class="flex items-start space-x-3">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">
                                        {{ $encuesta->preguntas->count() + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-medium text-gray-900 mb-2" x-text="pregunta_texto || 'Escribe tu pregunta arriba para ver la vista previa'">
                                        </h5>
                                        
                                        <div x-show="tipo_pregunta === 'opcion_multiple'">
                                            <div class="space-y-2">
                                                <template x-for="(opcion, index) in opciones" :key="index">
                                                    <label class="flex items-center space-x-2" x-show="opcion.trim()">
                                                        <input type="radio" class="h-4 w-4 text-orange-600 border-gray-300" disabled>
                                                        <span class="text-sm text-gray-700" x-text="opcion || 'Opción ' + (index + 1)"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        <div x-show="tipo_pregunta === 'texto_libre'">
                                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" 
                                                      rows="3" 
                                                      placeholder="Los usuarios escribirán su respuesta aquí..." 
                                                      disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Consejos para crear buenas preguntas:</h3>
                                    <div class="mt-1 text-sm text-blue-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Usa un lenguaje claro y evita tecnicismos innecesarios</li>
                                            <li>Haz una pregunta a la vez</li>
                                            <li>Para opciones múltiples, asegúrate de que sean mutuamente excluyentes</li>
                                            <li>Considera agregar una opción "Otro" si es apropiado</li>
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
                                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-md transition-colors"
                                :disabled="!pregunta_texto.trim() || (tipo_pregunta === 'opcion_multiple' && opciones.filter(o => o.trim()).length === 0)">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Agregar Pregunta
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