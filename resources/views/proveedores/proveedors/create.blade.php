<x-app-layout>
    <x-page-header title="Crear Nuevo Proveedor" />
    

    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('proveedores.proveedors.store') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    {{ csrf_field() }}
                    
                    <!-- Header del formulario -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Datos del Proveedor</h3>
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
                                <a href="{{ route('proveedores.proveedors.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido del formulario -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            <!-- Columna izquierda - Datos principales -->
                            <div class="space-y-6">
                                <div class="border-b border-gray-200 pb-4">
                                    <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Información Principal</h4>
                                </div>
                                
                                <!-- Razón Social -->
                                <div>
                                    <label for="razonsocial" class="block text-sm font-medium text-gray-700 mb-2">
                                        Razón Social <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="razonsocial"
                                           name="razonsocial" 
                                           value="{{ old('razonsocial') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('razonsocial') border-red-300 @enderror" 
                                           placeholder="Ingrese la razón social"
                                           required 
                                           autocomplete="off">
                                    @error('razonsocial')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CUIT -->
                                <div>
                                    <label for="cuit" class="block text-sm font-medium text-gray-700 mb-2">
                                        CUIT <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="cuit"
                                           name="cuit" 
                                           value="{{ old('cuit') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('cuit') border-red-300 @enderror" 
                                           placeholder="XX-XXXXXXXX-X"
                                           required 
                                           autocomplete="off">
                                    @error('cuit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Correo Institucional -->
                                <div>
                                    <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo Institucional <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="correo"
                                           name="correo" 
                                           value="{{ old('correo') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('correo') border-red-300 @enderror" 
                                           placeholder="contacto@empresa.com"
                                           required 
                                           autocomplete="off">
                                    @error('correo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre de Fantasía -->
                                <div>
                                    <label for="fantasia" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre de Fantasía
                                    </label>
                                    <input type="text" 
                                           id="fantasia"
                                           name="fantasia" 
                                           value="{{ old('fantasia') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('fantasia') border-red-300 @enderror" 
                                           placeholder="Nombre comercial"
                                           autocomplete="off">
                                    @error('fantasia')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Columna derecha - Datos de contacto -->
                            <div class="space-y-6">
                                <div class="border-b border-gray-200 pb-4">
                                    <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Información de Contacto</h4>
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono
                                    </label>
                                    <input type="text" 
                                           id="telefono"
                                           name="telefono" 
                                           value="{{ old('telefono') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('telefono') border-red-300 @enderror" 
                                           placeholder="+54 11 1234-5678"
                                           autocomplete="off">
                                    @error('telefono')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sitio Web -->
                                <div>
                                    <label for="webpage" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sitio Web
                                    </label>
                                    <input type="url" 
                                           id="webpage"
                                           name="webpage" 
                                           value="{{ old('webpage') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('webpage') border-red-300 @enderror" 
                                           placeholder="https://www.empresa.com"
                                           autocomplete="off">
                                    @error('webpage')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Horario de atención -->
                                <div>
                                    <label for="horario" class="block text-sm font-medium text-gray-700 mb-2">
                                        Horario de Atención
                                    </label>
                                    <input type="text" 
                                           id="horario"
                                           name="horario" 
                                           value="{{ old('horario') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('horario') border-red-300 @enderror" 
                                           placeholder="Lunes a Viernes 9:00 - 18:00"
                                           autocomplete="off">
                                    @error('horario')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nota informativa -->
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">
                                                Información importante
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>Los campos marcados con <span class="text-red-500 font-medium">*</span> son obligatorios para crear el proveedor.</p>
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