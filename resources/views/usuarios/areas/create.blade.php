<x-app-layout>
  <x-page-header title="Crear Nueva Área">
      <x-slot:actions>
          <a href="{{ route('usuarios.areas.index') }}" 
             class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
              Volver al Listado
          </a>
      </x-slot:actions>
  </x-page-header>

  <div class="w-full max-w-2xl mx-auto">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <form action="{{ route('usuarios.areas.store') }}" method="POST" class="space-y-6">
              @csrf

              <div class="space-y-4">
                  <div>
                      <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                          Nombre del Área *
                      </label>
                      <input type="text" 
                             name="nombre" 
                             id="nombre"
                             class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                             placeholder="Ingrese el nombre del área"
                             required>
                      @error('nombre')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>
              </div>

              <!-- Botones de acción -->
              <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                  <a href="{{ route('usuarios.areas.index') }}" 
                     class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                      Cancelar
                  </a>
                  <button type="submit" 
                          class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                      <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                      </svg>
                      Crear Área
                  </button>
              </div>
          </form>
      </div>
  </div>
</x-app-layout>