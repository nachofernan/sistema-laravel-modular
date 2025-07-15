<x-app-layout>
  <x-page-header title="Editar Área - ID: {{ $area->id }}">
      <x-slot:actions>
          <a href="{{ route('usuarios.areas.index') }}" 
             class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
              Volver al Listado
          </a>
      </x-slot:actions>
  </x-page-header>

  <div class="w-full max-w-2xl mx-auto">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <form action="{{ route('usuarios.areas.update', $area) }}" method="POST" class="space-y-6">
              @method('PUT')
              @csrf

              <div class="space-y-4">
                  <div>
                      <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                          Nombre del Área *
                      </label>
                      <input type="text" 
                             name="nombre" 
                             id="nombre"
                             value="{{ old('nombre', $area->nombre) }}"
                             class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                             placeholder="Ingrese el nombre del área"
                             required>
                      @error('nombre')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>

                  <div>
                      <label for="area_id" class="block text-sm font-medium text-gray-700 mb-2">
                          Área Padre
                      </label>
                      <select name="area_id" 
                              id="area_id"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                          <option value="">Sin área padre</option>
                          @livewire('usuarios.areas.foreach-select', ['areas' => $areas, 'area_id' => $area->area_id, 'disabled' => true, 'nivel' => ''])
                      </select>
                      @error('area_id')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                      <p class="mt-1 text-xs text-gray-500">Seleccione un área padre si esta área es una sub-área</p>
                  </div>
              </div>

              <!-- Información adicional -->
              <div class="bg-gray-50 rounded-md p-4">
                  <h4 class="text-sm font-medium text-gray-900 mb-2">Información del área</h4>
                  <div class="text-sm text-gray-600 space-y-1">
                      <p><span class="font-medium">Personal asignado:</span> {{ count($area->users) }} persona(s)</p>
                      <p><span class="font-medium">Sub-áreas:</span> {{ count($area->hijos) }} área(s)</p>
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
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Actualizar Área
                  </button>
              </div>
          </form>
      </div>
  </div>
</x-app-layout>