<x-app-layout>
    <x-page-header title="Editar Vehículo">
        <x-slot:actions>
            <a href="{{ route('automotores.vehiculos.show', $vehiculo) }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Vehículo
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-4xl mx-auto">
        <form action="{{ route('automotores.vehiculos.update', $vehiculo) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header del formulario -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900">Información del Vehículo</h2>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Información actual -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Estado actual:</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Patente:</span>
                                <div class="font-medium text-gray-900">{{ $vehiculo->patente }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Kilometraje:</span>
                                <div class="font-medium text-gray-900">{{ number_format($vehiculo->kilometraje) }} km</div>
                            </div>
                            <div>
                                <span class="text-gray-600">COPRES:</span>
                                <div class="font-medium text-gray-900">{{ $vehiculo->copres->count() }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Registrado:</span>
                                <div class="font-medium text-gray-900">{{ $vehiculo->created_at->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Última actualización:</span>
                                <div class="font-medium text-gray-900">{{ $vehiculo->updated_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700 mb-2">
                            Marca *
                        </label>
                        <input type="text" 
                               name="marca" 
                               id="marca"
                               value="{{ old('marca', $vehiculo->marca) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('marca') border-red-500 @enderror" 
                               placeholder="Ej: Toyota, Ford, Chevrolet..."
                               required>
                        @error('marca')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modelo -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-gray-700 mb-2">
                            Modelo *
                        </label>
                        <input type="text" 
                               name="modelo" 
                               id="modelo"
                               value="{{ old('modelo', $vehiculo->modelo) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('modelo') border-red-500 @enderror" 
                               placeholder="Ej: Corolla, F-150, Silverado..."
                               required>
                        @error('modelo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Patente -->
                    <div>
                        <label for="patente" class="block text-sm font-medium text-gray-700 mb-2">
                            Patente *
                        </label>
                        <input type="text" 
                               name="patente" 
                               id="patente"
                               value="{{ old('patente', $vehiculo->patente) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('patente') border-red-500 @enderror" 
                               placeholder="Ej: ABC123, XY-123-ZW..."
                               required>
                        @error('patente')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kilometraje -->
                    <div>
                        <label for="kilometraje" class="block text-sm font-medium text-gray-700 mb-2">
                            Kilometraje
                        </label>
                        <input type="number" 
                               name="kilometraje" 
                               id="kilometraje"
                               min="0"
                               value="{{ old('kilometraje', $vehiculo->kilometraje) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('kilometraje') border-red-500 @enderror" 
                               placeholder="0">
                        @error('kilometraje')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end space-x-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('automotores.vehiculos.show', $vehiculo) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Vehículo
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
