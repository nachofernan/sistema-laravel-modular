<x-app-layout>
    <x-page-header title="Editar Documento">
        <x-slot:actions>
            <a href="{{ route('documentos.documentos.show', $documento) }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Ver Documento
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('documentos.documentos.update', $documento) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información básica -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                            Información del Documento
                        </h3>

                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Documento *
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre"
                                   value="{{ old('nombre', $documento->nombre) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Ingrese el nombre del documento"
                                   required>
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                                Código
                            </label>
                            <input type="text"
                                   name="codigo"
                                   id="codigo"
                                   value="{{ old('codigo', $documento->codigo) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Ej: L-07.2-003_v3">
                            @error('codigo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Codificación de Control de Gestión, tal como figura en el documento.
                            </p>
                        </div>

                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <input type="text" 
                                   name="descripcion" 
                                   id="descripcion"
                                   value="{{ old('descripcion', $documento->descripcion) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Descripción del documento">
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                Observaciones
                            </label>
                            <textarea name="observaciones"
                                      id="observaciones"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                      placeholder="Notas internas sobre el documento">{{ old('observaciones', $documento->observaciones) }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo
                            </label>
                            <input type="file" 
                                   name="archivo" 
                                   id="archivo"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('archivo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Deje en blanco para mantener el archivo actual. Al subir uno nuevo, el actual pasa
                                al historial de versiones (no se borra) y el documento pasa a v{{ $documento->version + 1 }}.
                            </p>
                            @if($documento->archivo)
                                <p class="mt-1 text-xs text-blue-600">
                                    Archivo actual (v{{ $documento->version }}): {{ $documento->archivo }}
                                </p>
                            @endif
                        </div>

                        <div>
                            <label for="notas_version" class="block text-sm font-medium text-gray-700 mb-2">
                                Qué cambia en esta versión
                            </label>
                            <input type="text"
                                   name="notas_version"
                                   id="notas_version"
                                   value="{{ old('notas_version') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Ej: actualización anual, corrección de anexo">
                            @error('notas_version')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Sólo se guarda si se sube un archivo nuevo. Queda en el historial.
                            </p>
                        </div>
                    </div>

                    <!-- Organización -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                            Organización y Acceso
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
                                    <option value="{{ $categoria->id }}" 
                                            {{ $categoria->id == $documento->categoria_id ? 'selected' : '' }}>
                                        {{ $categoria->padre->nombre }} → {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="orden" class="block text-sm font-medium text-gray-700 mb-2">
                                Orden
                            </label>
                            <input type="number"
                                   name="orden"
                                   id="orden"
                                   min="0"
                                   value="{{ old('orden', $documento->orden) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('orden')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Posición dentro de la categoría. Menor número, más arriba.
                            </p>
                        </div>

                        <div class="border-t border-gray-200 pt-4 space-y-3">
                            <h4 class="text-sm font-medium text-gray-900">Visibilidad</h4>

                            <div class="flex items-start">
                                <input type="checkbox"
                                       name="visible"
                                       id="visible"
                                       value="1"
                                       {{ old('visible', $documento->visible) ? 'checked' : '' }}
                                       class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="visible" class="ml-2 text-sm text-gray-700">
                                    Activo
                                    <span class="block text-xs text-gray-500">
                                        Si se desmarca, el documento queda dado de baja sin borrarse.
                                    </span>
                                </label>
                            </div>

                            <div class="flex items-start">
                                <input type="checkbox"
                                       name="publico"
                                       id="publico"
                                       value="1"
                                       {{ old('publico', $documento->publico) ? 'checked' : '' }}
                                       class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="publico" class="ml-2 text-sm text-gray-700">
                                    Publicar sin iniciar sesión
                                    <span class="block text-xs text-gray-500">
                                        Queda descargable desde el portal público, siempre que su categoría también lo esté.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del documento -->
                <div class="bg-gray-50 rounded-md p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Información del documento</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Descargas:</span> {{ $documento->descargas->count() }}
                        </div>
                        <div>
                            <span class="font-medium">Creado por:</span> {{ $documento->user->realname }}
                        </div>
                        <div>
                            <span class="font-medium">Subido:</span> 
                            {{ $documento->archivo_uploaded_at ? \Carbon\Carbon::parse($documento->archivo_uploaded_at)->format('d/m/Y H:i') : 'No registrado' }}
                        </div>
                        <div>
                            <span class="font-medium">Tipo:</span> {{ $documento->mimeType }} ({{ $documento->extension }})
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('documentos.documentos.show', $documento) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>