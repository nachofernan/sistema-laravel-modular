<x-app-layout>
    <x-page-header title="Crear Nuevo Elemento">
        <x-slot:actions>
            <a href="{{ route('inventario.elementos.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('inventario.elementos.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Información General -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                            Información General
                        </h3>

                        <div>
                            <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Categoría *
                            </label>
                            <select name="categoria_id" 
                                    id="categoria_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required>
                                <option value="">Seleccione una categoría</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }} ({{ $categoria->prefijo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="estado_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado *
                            </label>
                            <select name="estado_id" 
                                    id="estado_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}" {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Usuario Asignado
                            </label>
                            <select name="user_id" 
                                    id="user_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sin asignar</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->realname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sede_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sede
                            </label>
                            <select name="sede_id" 
                                    id="sede_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sin asignar</option>
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede->id }}" {{ old('sede_id') == $sede->id ? 'selected' : '' }}>
                                        {{ $sede->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sede_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="area_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Área
                            </label>
                            <select name="area_id" 
                                    id="area_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sin asignar</option>
                                @livewire('inventario.areas.foreach-select', ['areas' => $areas, 'area_id' => old('area_id', 0), 'disabled' => false, 'nivel' => ''])
                            </select>
                            @error('area_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="proveedor" class="block text-sm font-medium text-gray-700 mb-2">
                                Proveedor
                            </label>
                            <input type="text" 
                                   name="proveedor" 
                                   id="proveedor"
                                   value="{{ old('proveedor') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Nombre del proveedor">
                            @error('proveedor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="soporte" class="block text-sm font-medium text-gray-700 mb-2">
                                Soporte
                            </label>
                            <input type="text" 
                                   name="soporte" 
                                   id="soporte"
                                   value="{{ old('soporte') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Información de soporte">
                            @error('soporte')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vencimiento_garantia" class="block text-sm font-medium text-gray-700 mb-2">
                                Vencimiento de Garantía
                            </label>
                            <input type="date" 
                                   name="vencimiento_garantia" 
                                   id="vencimiento_garantia"
                                   value="{{ old('vencimiento_garantia') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('vencimiento_garantia')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notas" class="block text-sm font-medium text-gray-700 mb-2">
                                Notas
                            </label>
                            <textarea name="notas" 
                                      id="notas"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="Notas adicionales del elemento">{{ old('notas') }}</textarea>
                            @error('notas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                            Información Adicional
                        </h3>

                        <div class="bg-blue-50 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Información sobre la creación de elementos
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>El código del elemento se generará automáticamente basado en la categoría seleccionada</li>
                                            <li>Las características específicas se podrán configurar después de crear el elemento</li>
                                            <li>Se registrará automáticamente quién creó el elemento y cuándo</li>
                                            <li>Los campos marcados con * son obligatorios</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('inventario.elementos.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Elemento
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>