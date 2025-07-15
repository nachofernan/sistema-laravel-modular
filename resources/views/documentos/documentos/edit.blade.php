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
                            <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                                Versión
                            </label>
                            <input type="text" 
                                   name="version" 
                                   id="version"
                                   value="{{ old('version', $documento->version) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Ej: 1.0, v2.1, etc.">
                            @error('version')
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
                                Deje en blanco para mantener el archivo actual. Seleccione un archivo para reemplazarlo.
                            </p>
                            @if($documento->archivo)
                                <p class="mt-1 text-xs text-blue-600">
                                    Archivo actual: {{ $documento->archivo }}
                                </p>
                            @endif
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
                            <label for="sede_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sede
                            </label>
                            <select name="sede_id" 
                                    id="sede_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Todas las sedes</option>
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede->id }}" 
                                            {{ $sede->id == $documento->sede_id ? 'selected' : '' }}>
                                        {{ $sede->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sede_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Configuración Avanzada</h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="orden" class="block text-sm font-medium text-gray-700 mb-2">
                                        Orden
                                    </label>
                                    <input type="number" 
                                           name="orden" 
                                           id="orden"
                                           value="{{ old('orden', $documento->orden) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="0">
                                    @error('orden')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="visible" class="block text-sm font-medium text-gray-700 mb-2">
                                        Visibilidad
                                    </label>
                                    <select name="visible" 
                                            id="visible"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="1" {{ $documento->visible ? 'selected' : '' }}>Visible</option>
                                        <option value="0" {{ !$documento->visible ? 'selected' : '' }}>Oculto</option>
                                    </select>
                                    @error('visible')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
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