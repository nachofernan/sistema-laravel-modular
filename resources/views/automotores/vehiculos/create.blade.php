<x-app-layout>
    <x-page-header title="Registrar Nuevo Vehículo">
        <x-slot:actions>
            <a href="{{ route('automotores.vehiculos.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-4xl mx-auto">
        <form action="{{ route('automotores.vehiculos.store') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-6">
                    <svg class="h-6 w-6 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0H4"></path>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900">Datos del Vehículo</h2>
                </div>

                <div class="space-y-6">
                    <!-- Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700 mb-2">
                            Marca *
                        </label>
                        <input type="text" 
                               name="marca" 
                               id="marca"
                               value="{{ old('marca') }}" 
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
                               value="{{ old('modelo') }}" 
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
                               value="{{ old('patente') }}" 
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
                               value="{{ old('kilometraje', 0) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('kilometraje') border-red-500 @enderror" 
                               placeholder="0">
                        @error('kilometraje')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('automotores.vehiculos.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Registrar Vehículo
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
