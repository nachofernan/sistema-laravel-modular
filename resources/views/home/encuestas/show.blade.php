<x-app-layout>
    <div class="w-full max-w-4xl mx-auto px-4 py-6">
        <!-- Header de la encuesta -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $encuesta->nombre }}</h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                            <a href="{{ route('home.capacitacions.show', $encuesta->capacitacion) }}" 
                               class="text-purple-600 hover:text-purple-800 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver a {{ $encuesta->capacitacion->nombre }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        {{ $encuesta->preguntas->count() }} preguntas
                    </span>
                </div>
            </div>
            
            @if($encuesta->descripcion)
            <div class="mt-4 pt-4 border-t border-purple-200">
                <div class="text-gray-700 leading-relaxed">
                    {!! nl2br($encuesta->descripcion) !!}
                </div>
            </div>
            @endif
        </div>

        <!-- Formulario de la encuesta -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Preguntas de la Encuesta</h2>
                    <div class="text-sm text-gray-500">
                        Completa todas las preguntas y envía tu respuesta
                    </div>
                </div>
            </div>

            <form action="{{ route('home.encuestas.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-8">
                    @foreach ($encuesta->preguntas as $key => $pregunta)
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start space-x-3 mb-4">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-800 text-sm font-medium rounded-full">
                                    {{ $key + 1 }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 leading-6">
                                    {{ $pregunta->pregunta }}
                                </h3>
                                @if ($pregunta->con_opciones)
                                    <p class="text-sm text-gray-500 mt-1">Selecciona una opción:</p>
                                @else
                                    <p class="text-sm text-gray-500 mt-1">Escribe tu respuesta:</p>
                                @endif
                            </div>
                        </div>

                        <div class="ml-11">
                            @if ($pregunta->con_opciones)
                                <!-- Opciones de selección -->
                                <div class="space-y-3">
                                    @foreach ($pregunta->opciones as $opcion)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="radio" 
                                               name="{{ $pregunta->id }}" 
                                               value="{{ $opcion->id }}" 
                                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300"
                                               required>
                                        <span class="ml-3 text-sm text-gray-700">{{ $opcion->opcion }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            @else
                                <!-- Texto libre -->
                                <div>
                                    <textarea name="{{ $pregunta->id }}" 
                                              rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" 
                                              placeholder="Escribe tu respuesta aquí..."
                                              required></textarea>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('home.capacitacions.show', $encuesta->capacitacion) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver sin Guardar
                    </a>
                    
                    <div class="flex items-center space-x-3">
                        <div class="text-sm text-gray-500">
                            {{ $encuesta->preguntas->count() }} preguntas • Obligatorias
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Enviar Encuesta
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Instrucciones adicionales -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Instrucciones importantes:</h3>
                    <div class="mt-1 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Todas las preguntas son obligatorias</li>
                            <li>Una vez enviada la encuesta no podrás modificar tus respuestas</li>
                            <li>Asegúrate de revisar todas tus respuestas antes de enviar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>