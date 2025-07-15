<x-app-layout>
  <x-page-header title="Crear Nueva Categoría de Documentos">
      <x-slot:actions>
          <a href="{{ route('documentos.categorias.index') }}" 
             class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
              Volver al Listado
          </a>
      </x-slot:actions>
  </x-page-header>

  <div class="w-full max-w-2xl mx-auto">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <form action="{{ route('documentos.categorias.store') }}" method="POST" class="space-y-6">
              @csrf

              <div class="space-y-4">
                  <div>
                      <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                          Nombre de la Categoría *
                      </label>
                      <input type="text" 
                             name="nombre" 
                             id="nombre"
                             class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                             placeholder="Ingrese el nombre de la categoría"
                             required>
                      @error('nombre')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>

                  <div>
                      <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-2">
                          Categoría Padre
                      </label>
                      <select name="categoria_id" 
                              id="categoria_id"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                          <option value="">Es categoría principal</option>
                          @foreach ($categorias as $categoria)
                              <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                          @endforeach
                      </select>
                      @error('categoria_id')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                      <p class="mt-1 text-xs text-gray-500">
                          Seleccione una categoría padre si desea crear una subcategoría. Deje en blanco para crear una categoría principal.
                      </p>
                  </div>
              </div>

              <!-- Información adicional -->
              <div class="bg-blue-50 rounded-md p-4">
                  <div class="flex">
                      <div class="flex-shrink-0">
                          <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                          </svg>
                      </div>
                      <div class="ml-3">
                          <h3 class="text-sm font-medium text-blue-800">
                              Organización de Categorías
                          </h3>
                          <div class="mt-2 text-sm text-blue-700">
                              <p>Las categorías permiten organizar los documentos de forma jerárquica. Primero cree las categorías principales y luego las subcategorías específicas.</p>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Botones de acción -->
              <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                  <a href="{{ route('documentos.categorias.index') }}" 
                     class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                      Cancelar
                  </a>
                  <button type="submit" 
                          class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                      <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                      </svg>
                      Crear Categoría
                  </button>
              </div>
          </form>
      </div>
  </div>
</x-app-layout>