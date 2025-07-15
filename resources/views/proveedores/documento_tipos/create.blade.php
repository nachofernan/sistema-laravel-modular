<x-app-layout>
    <!-- Header -->
    <x-page-header title="Crear Nuevo Tipo de Documento" />
    
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('proveedores.documento_tipos.store') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    {{ csrf_field() }}
                    
                    <!-- Header del formulario -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Datos del Tipo de Documento</h3>
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                        :disabled="loading"
                                        :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                    <span x-show="!loading">Guardar</span>
                                    <span x-show="loading" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Guardando...
                                    </span>
                                </button>
                                <a href="{{ route('proveedores.documento_tipos.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido del formulario -->
                    <div class="p-6">
                        <div class="max-w-2xl mx-auto">
                            <div class="space-y-6">
                                
                                <div class="border-b border-gray-200 pb-4">
                                    <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Información del Documento</h4>
                                </div>
                                
                                <!-- Código -->
                                <div>
                                    <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Código <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="codigo"
                                           name="codigo" 
                                           value="{{ old('codigo') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('codigo') border-red-300 @enderror" 
                                           placeholder="Ingrese el código del documento"
                                           required 
                                           autocomplete="off">
                                    @error('codigo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="nombre"
                                           name="nombre" 
                                           value="{{ old('nombre') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nombre') border-red-300 @enderror" 
                                           placeholder="Ingrese el nombre del documento"
                                           required 
                                           autocomplete="off">
                                    @error('nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Vencimiento -->
                                <div>
                                    <label for="vencimiento" class="block text-sm font-medium text-gray-700 mb-2">
                                        ¿Tiene vencimiento?
                                    </label>
                                    <select name="vencimiento" 
                                            id="vencimiento"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="0" {{ old('vencimiento') == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('vencimiento') == '1' ? 'selected' : '' }}>Sí</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Especifica si este tipo de documento tiene fecha de vencimiento</p>
                                </div>

                                <!-- Nota informativa -->
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">
                                                Información importante
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>Los campos marcados con <span class="text-red-500 font-medium">*</span> son obligatorios para crear el tipo de documento.</p>
                                                <p class="mt-1">El código debe ser único y se utilizará para identificar este tipo de documento en el sistema.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>