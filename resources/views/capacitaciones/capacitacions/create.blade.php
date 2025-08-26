<x-app-layout>
    <x-page-header title="Crear Nueva Capacitación">
        <x-slot:actions>
            <a href="{{ route('capacitaciones.capacitacions.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-4xl mx-auto">
        <form action="{{ route('capacitaciones.capacitacions.store') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-6">
                    <svg class="h-6 w-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900">Datos de la Capacitación</h2>
                </div>

                <div class="space-y-6">
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre *
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre"
                               value="{{ old('nombre') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre') border-red-500 @enderror" 
                               placeholder="Ingrese el nombre de la capacitación"
                               required>
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fechas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Inicio *
                            </label>
                            <input type="date" 
                                   name="fecha_inicio" 
                                   id="fecha_inicio"
                                   value="{{ old('fecha_inicio') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fecha_inicio') border-red-500 @enderror" 
                                   required>
                            @error('fecha_inicio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fecha_final" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha Final *
                            </label>
                            <input type="date" 
                                   name="fecha_final" 
                                   id="fecha_final"
                                   value="{{ old('fecha_final') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fecha_final') border-red-500 @enderror" 
                                   required>
                            @error('fecha_final')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea name="descripcion" 
                                  id="descripcion"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('descripcion') border-red-500 @enderror" 
                                  placeholder="Descripción de la capacitación (opcional)">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('capacitaciones.capacitacions.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Capacitación
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript para validación de fechas -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaFinalInput = document.getElementById('fecha_final');

            /* // Establecer fecha mínima para fecha_inicio como hoy
            const today = new Date().toISOString().split('T')[0];
            fechaInicioInput.min = today; */

            // Actualizar fecha mínima de fecha_final cuando cambie fecha_inicio
            fechaInicioInput.addEventListener('change', function() {
                fechaFinalInput.min = this.value;
                
                // Si fecha_final es anterior a fecha_inicio, limpiar fecha_final
                if (fechaFinalInput.value && fechaFinalInput.value < this.value) {
                    fechaFinalInput.value = '';
                }
            });

            // Validar que fecha_final no sea anterior a fecha_inicio
            fechaFinalInput.addEventListener('change', function() {
                if (this.value && fechaInicioInput.value && this.value < fechaInicioInput.value) {
                    alert('La fecha final no puede ser anterior a la fecha de inicio');
                    this.value = '';
                }
            });
        });
    </script>
</x-app-layout>