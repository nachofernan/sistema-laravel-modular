<x-app-layout>
    <x-page-header title="Editar Capacitación">
        <x-slot:subtitle>
            Modificar información de: {{ $capacitacion->nombre }}
        </x-slot:subtitle>
        <x-slot:actions>
            <a href="{{ route('capacitaciones.capacitacions.show', $capacitacion) }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver a la Capacitación
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-4xl mx-auto">
        <form action="{{ route('capacitaciones.capacitacions.update', $capacitacion) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header del formulario -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900">Información de la Capacitación</h2>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Información actual -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Estado actual:</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Fecha original:</span>
                                <div class="font-medium text-gray-900">{{ Carbon\Carbon::create($capacitacion->fecha)->format('d-m-Y') }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Invitados:</span>
                                <div class="font-medium text-gray-900">{{ $capacitacion->invitaciones->count() }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Documentos:</span>
                                <div class="font-medium text-gray-900">{{ $capacitacion->documentos->count() }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Encuestas:</span>
                                <div class="font-medium text-gray-900">{{ $capacitacion->encuestas->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la capacitación *
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre"
                               value="{{ old('nombre', $capacitacion->nombre) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre') border-red-500 @enderror" 
                               required>
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de la capacitación *
                        </label>
                        <input type="date" 
                               name="fecha" 
                               id="fecha"
                               value="{{ old('fecha', $capacitacion->fecha) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fecha') border-red-500 @enderror" 
                               required>
                        @error('fecha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        @php
                            $fechaCapacitacion = \Carbon\Carbon::parse($capacitacion->fecha);
                            $yaOcurrio = $fechaCapacitacion->isPast();
                        @endphp
                        
                        @if($yaOcurrio)
                        <div class="mt-2 bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Cambio de fecha</h3>
                                    <div class="mt-1 text-sm text-yellow-700">
                                        Esta capacitación ya ocurrió. Cambiar la fecha puede afectar reportes y estadísticas existentes.
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea name="descripcion" 
                                  id="descripcion"
                                  rows="5" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('descripcion') border-red-500 @enderror"
                                  placeholder="Describe el contenido y objetivos de la capacitación...">{{ old('descripcion', $capacitacion->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Esta descripción será visible para todos los participantes
                        </p>
                    </div>

                    <!-- Información de impacto -->
                    @if($capacitacion->invitaciones->count() > 0 || $capacitacion->encuestas->count() > 0)
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Impacto de los cambios:</h3>
                                <div class="mt-1 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @if($capacitacion->invitaciones->count() > 0)
                                        <li>{{ $capacitacion->invitaciones->count() }} usuarios invitados verán los cambios</li>
                                        @endif
                                        @if($capacitacion->encuestas->count() > 0)
                                        <li>{{ $capacitacion->encuestas->count() }} encuestas asociadas mantendrán su configuración</li>
                                        @endif
                                        <li>Los documentos y materiales no se verán afectados</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Vista previa de cambios -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Vista previa:</h3>
                        <div class="bg-white border border-gray-200 rounded-md p-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900" id="preview-nombre">{{ $capacitacion->nombre }}</h4>
                                    <div class="text-xs text-gray-500" id="preview-fecha">{{ Carbon\Carbon::create($capacitacion->fecha)->format('d-m-Y') }}</div>
                                    <div class="text-xs text-gray-600 mt-1" id="preview-descripcion">
                                        {{ $capacitacion->descripcion ? Str::limit($capacitacion->descripcion, 80) : 'Sin descripción' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        Última modificación: {{ $capacitacion->updated_at->diffForHumans() }}
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('capacitaciones.capacitacions.show', $capacitacion) }}" 
                           class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript para vista previa en tiempo real -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nombreInput = document.getElementById('nombre');
            const fechaInput = document.getElementById('fecha');
            const descripcionInput = document.getElementById('descripcion');
            
            const previewNombre = document.getElementById('preview-nombre');
            const previewFecha = document.getElementById('preview-fecha');
            const previewDescripcion = document.getElementById('preview-descripcion');
            
            nombreInput.addEventListener('input', function() {
                previewNombre.textContent = this.value || 'Nombre de la capacitación';
            });
            
            fechaInput.addEventListener('input', function() {
                if (this.value) {
                    const fecha = new Date(this.value);
                    previewFecha.textContent = fecha.toLocaleDateString('es-ES');
                }
            });
            
            descripcionInput.addEventListener('input', function() {
                const descripcion = this.value || 'Sin descripción';
                previewDescripcion.textContent = descripcion.length > 80 
                    ? descripcion.substring(0, 80) + '...' 
                    : descripcion;
            });
        });
    </script>
</x-app-layout>