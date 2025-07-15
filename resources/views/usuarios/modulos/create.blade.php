<x-app-layout>
    <x-page-header title="Crear Nuevo Módulo">
        <x-slot:actions>
            <a href="{{ route('usuarios.modulos.index') }}" 
            class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver
            </a>
        </x-slot:actions>
    </x-page-header>
    
    <div class="w-full max-w-7xl mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('usuarios.modulos.store') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    {{ csrf_field() }}
                    
                    <!-- Header del formulario -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Información del Módulo</h3>
                                <p class="mt-1 text-sm text-gray-500">Configure los datos básicos del nuevo módulo del sistema</p>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                        :disabled="loading"
                                        :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                    <span x-show="!loading">
                                        Crear Módulo
                                    </span>
                                    <span x-show="loading" class="flex items-center">
                                        Creando...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido del formulario -->
                    <div class="p-6">
                        <div class="max-w-2xl mx-auto">
                            <div class="space-y-6">
                                
                                <!-- Nombre del Módulo -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre del Módulo <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="nombre"
                                           name="nombre" 
                                           value="{{ old('nombre') }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nombre') border-red-300 @enderror" 
                                           placeholder="ej: Usuarios, Proveedores, Inventario"
                                           required 
                                           autocomplete="off">
                                    @error('nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Este nombre se utilizará para generar los roles y permisos automáticamente</p>
                                </div>

                                <!-- Descripción del Módulo -->
                                <div>
                                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descripción del Módulo
                                    </label>
                                    <textarea id="descripcion"
                                              name="descripcion" 
                                              rows="3"
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('descripcion') border-red-300 @enderror"
                                              placeholder="Describe las funcionalidades y propósito de este módulo...">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Opcional: Ayuda a identificar el propósito del módulo</p>
                                </div>

                                <!-- Estado del Módulo -->
                                <div>
                                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                        Estado Inicial <span class="text-red-500">*</span>
                                    </label>
                                    <select id="estado" 
                                            name="estado" 
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('estado') border-red-300 @enderror"
                                            required>
                                        @foreach (\App\Models\Usuarios\Modulo::getEstados() as $estado)
                                            <option value="{{ $estado }}" {{ old('estado', 'activo') == $estado ? 'selected' : '' }}>
                                                {{ ucfirst($estado) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estado')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-2 text-xs text-gray-500">
                                        <div class="space-y-1">
                                            <div><strong>Activo:</strong> El módulo está operativo y disponible</div>
                                            <div><strong>Mantenimiento:</strong> El módulo está en desarrollo o actualización</div>
                                            <div><strong>Inactivo:</strong> El módulo está deshabilitado temporalmente</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información importante -->
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">
                                                Información importante sobre módulos
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Una vez creado, podrá agregar roles y permisos específicos</li>
                                                    <li>El nombre del módulo debe ser único en el sistema</li>
                                                    <li>Puede cambiar el estado del módulo en cualquier momento</li>
                                                    <li>Los módulos en mantenimiento aparecerán marcados para los usuarios</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview del naming -->
                                <div class="bg-gray-50 border border-gray-200 rounded-md p-4" x-data="{ moduleName: '{{ old('nombre', '') }}' }">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Vista previa de nomenclatura</h4>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <div>
                                            <strong>Ejemplo de rol:</strong> 
                                            <code class="bg-gray-200 px-2 py-1 rounded text-xs" 
                                                  x-text="moduleName ? (moduleName.charAt(0).toUpperCase() + moduleName.slice(1) + '/Modelo/Accion') : 'Modulo/Modelo/Accion'">
                                            </code>
                                        </div>
                                        <div>
                                            <strong>Ejemplo de permiso:</strong> 
                                            <code class="bg-gray-200 px-2 py-1 rounded text-xs"
                                                  x-text="moduleName ? (moduleName.charAt(0).toUpperCase() + moduleName.slice(1) + '/Modelo/Crear') : 'Modulo/Modelo/Crear'">
                                            </code>
                                        </div>
                                    </div>
                                    
                                    <!-- Script para actualizar preview -->
                                    <script>
                                        document.getElementById('nombre').addEventListener('input', function(e) {
                                            // Trigger Alpine.js reactivity
                                            document.querySelector('[x-data]').__x.$data.moduleName = e.target.value;
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>