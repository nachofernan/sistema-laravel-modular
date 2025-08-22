<div>
    <!-- Botón para abrir modal -->
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Agregar Encuesta
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
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Crear Nueva Encuesta
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
                <form action="{{ route('capacitaciones.encuestas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="capacitacion_id" value="{{ $capacitacion->id }}">
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- Nombre de la encuesta -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de la encuesta *
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                                   placeholder="Ej: Evaluación de satisfacción"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">
                                El nombre será visible para los participantes
                            </p>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción *
                            </label>
                            <textarea name="descripcion" 
                                      id="descripcion"
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                      placeholder="Explica el propósito de esta encuesta..."
                                      required></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                Proporciona contexto sobre qué información se busca obtener
                            </p>
                        </div>

                        <!-- Opciones de configuración -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-medium text-gray-900">Configuración inicial:</h4>
                            
                            <!-- Estado inicial -->
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="publicar_inmediatamente" 
                                       id="publicar_inmediatamente"
                                       value="1"
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="publicar_inmediatamente" class="text-sm text-gray-700">
                                    Publicar inmediatamente (disponible para responder)
                                </label>
                            </div>
                            
                            <p class="text-xs text-gray-500 ml-7">
                                Si no se marca, la encuesta se creará en estado "borrador" y deberás activarla manualmente
                            </p>
                        </div>

                        <!-- Próximos pasos -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Próximos pasos:</h3>
                                    <div class="mt-1 text-sm text-blue-700">
                                        <ol class="list-decimal pl-5 space-y-1">
                                            <li>Se creará la encuesta con la información básica</li>
                                            <li>Podrás agregar preguntas desde la vista de la encuesta</li>
                                            <li>Una vez completa, puedes activarla para los participantes</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de participantes -->
                        <div class="bg-green-50 border border-green-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Participantes:</h3>
                                    <div class="mt-1 text-sm text-green-700">
                                        Solo los usuarios marcados como "presentes" en la capacitación podrán responder esta encuesta.
                                        <br>
                                        <strong>Actualmente:</strong> {{ $capacitacion->invitaciones->where('presente', 1)->count() }} usuarios presentes.
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
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Encuesta
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