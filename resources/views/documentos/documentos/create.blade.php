<x-app-layout>
    <x-page-header title="Crear Nuevo Documento">
        <x-slot:actions>
            <a href="{{ route('documentos.documentos.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('documentos.documentos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

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
                                   value="{{ old('codigo') }}"
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
                                   value="{{ old('descripcion') }}"
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
                                      placeholder="Notas internas sobre el documento">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo *
                            </label>
                            <input type="file" 
                                   name="archivo" 
                                   id="archivo"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                            @error('archivo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Hasta 50 MB. Formatos permitidos: PDF, Word, Excel, PowerPoint, texto, imágenes y MP4.
                            </p>
                        </div>

                        <div>
                            <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                                Versión *
                            </label>
                            <input type="number"
                                   name="version"
                                   id="version"
                                   min="1"
                                   value="{{ old('version', 1) }}"
                                   class="w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   required>
                            @error('version')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Si el documento ya viene versionado de Control de Gestión, poné ese número.
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
                                    <option value="{{ $categoria->id }}">
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
                                   value="{{ old('orden', 1000) }}"
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
                                       {{ old('visible', true) ? 'checked' : '' }}
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
                                       {{ old('publico') ? 'checked' : '' }}
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
                                Información sobre la subida de documentos
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Los documentos se almacenan de forma segura en el servidor</li>
                                    <li>Se registra automáticamente quién subió el documento y cuándo</li>
                                    <li>Los usuarios pueden descargar los documentos según sus permisos</li>
                                    <li>Las descargas se registran para estadísticas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('documentos.documentos.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 10v6m0 0l-3-3m3 3l3-3"></path>
                        </svg>
                        Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>