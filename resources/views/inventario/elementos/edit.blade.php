<x-app-layout>
    <x-page-header title="Editar Elemento">
        <x-slot:actions>
            <a href="{{ route('inventario.elementos.show', $elemento) }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Ver Elemento
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-6xl mx-auto">
        <!-- Información del elemento -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex items-center">
                @if ($elemento->categoria->icono)
                    <div class="mr-3">
                        {!! $elemento->categoria->icono !!}
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $elemento->codigo }}</h2>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <span>{{ $elemento->categoria->nombre }}</span>
                        <span>•</span>
                        <span>{{ $elemento->estado->nombre }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('inventario.elementos.update', $elemento) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Información General -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                            Información General
                        </h3>

                        <div>
                            <label for="estado_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado *
                            </label>
                            <select name="estado_id" 
                                    id="estado_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required>
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}" 
                                            {{ $estado->id == $elemento->estado->id ? 'selected' : '' }}>
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
                                    <option value="{{ $user->id }}" 
                                            {{ $user->id == $elemento->user_id ? 'selected' : '' }}>
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
                                    <option value="{{ $sede->id }}" 
                                            {{ $sede->id == $elemento->sede_id ? 'selected' : '' }}>
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
                                @livewire('inventario.areas.foreach-select', ['areas' => $areas, 'area_id' => $elemento->area_id, 'disabled' => false, 'nivel' => ''])
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
                                   value="{{ old('proveedor', $elemento->proveedor) }}"
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
                                   value="{{ old('soporte', $elemento->soporte) }}"
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
                                   value="{{ old('vencimiento_garantia', $elemento->vencimiento_garantia) }}"
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
                                      placeholder="Notas adicionales del elemento">{{ old('notas', $elemento->notas) }}</textarea>
                            @error('notas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Características -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                            Características de {{ $elemento->categoria->nombre }}
                        </h3>

                        @forelse ($elemento->categoria->caracteristicas as $caracteristica)
                            <div>
                                <label for="valor_{{ $caracteristica->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $caracteristica->nombre }}
                                </label>
                                
                                @if ($caracteristica->con_opciones)
                                    <select name="valor[{{ $elemento->findValor($caracteristica->id)->id ?? 'c' . $caracteristica->id }}]"
                                            id="valor_{{ $caracteristica->id }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Seleccione una opción</option>
                                        @foreach ($caracteristica->opciones as $opcion)
                                            <option value="{{ $opcion->nombre }}" 
                                                    {{ ($elemento->findValor($caracteristica->id) && $elemento->findValor($caracteristica->id)->valor == $opcion->nombre) ? 'selected' : '' }}>
                                                {{ $opcion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" 
                                           name="valor[{{ $elemento->findValor($caracteristica->id)->id ?? 'c' . $caracteristica->id }}]"
                                           id="valor_{{ $caracteristica->id }}"
                                           value="{{ $elemento->findValor($caracteristica->id)->valor ?? '' }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Ingrese el valor">
                                @endif
                            </div>
                        @empty
                            <div class="bg-gray-50 rounded-md p-4">
                                <p class="text-sm text-gray-500 italic">
                                    Esta categoría no tiene características definidas.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('inventario.elementos.show', $elemento) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Elemento
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>