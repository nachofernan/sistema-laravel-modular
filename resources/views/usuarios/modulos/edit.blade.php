<x-app-layout>
    <x-page-header title="Editar Módulo">
        <x-slot:subtitle>
            {{ $modulo->nombre }} - ID #{{ $modulo->id }}
        </x-slot:subtitle>
        <x-slot:actions>
            <a href="{{ route('usuarios.modulos.show', $modulo) }}" 
            class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver
            </a>
        </x-slot:actions>
    </x-page-header>
    
    <div class="w-full max-w-7xl mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('usuarios.modulos.update', $modulo) }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    
                    <!-- Header del formulario -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Editar Información del Módulo</h3>
                                <p class="mt-1 text-sm text-gray-500">Modifique los datos del módulo {{ $modulo->nombre }}</p>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                        :disabled="loading"
                                        :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                    <span x-show="!loading">
                                        Actualizar Módulo
                                    </span>
                                    <span x-show="loading" class="flex items-center">
                                        Actualizando...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido del formulario -->
                    <div class="p-6">
                        <div class="max-w-2xl mx-auto">
                            <div class="space-y-6">
                                
                                <!-- Información del sistema -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Información del Sistema</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium">Creado:</span> 
                                            {{ $modulo->created_at ? Carbon\Carbon::create($modulo->created_at)->format('d-m-Y H:i') : 'N/A' }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Última modificación:</span> 
                                            {{ $modulo->updated_at && $modulo->updated_at != $modulo->created_at ? Carbon\Carbon::create($modulo->updated_at)->format('d-m-Y H:i') : 'Sin modificaciones' }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Roles asociados:</span> 
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $modulo->roles()->count() }}
                                            </span>
                                        </div>
                                        <div>
                                            <span class="font-medium">Permisos asociados:</span> 
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $modulo->permisos()->count() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Nombre del Módulo -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre del Módulo <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="nombre"
                                           name="nombre" 
                                           value="{{ old('nombre', $modulo->nombre) }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nombre') border-red-300 @enderror" 
                                           placeholder="ej: Usuarios, Proveedores, Inventario"
                                           required 
                                           autocomplete="off">
                                    @error('nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">
                                        ⚠️ Cambiar el nombre afectará la nomenclatura de roles y permisos existentes
                                    </p>
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
                                              placeholder="Describe las funcionalidades y propósito de este módulo...">{{ old('descripcion', $modulo->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Opcional: Ayuda a identificar el propósito del módulo</p>
                                </div>

                                <!-- Estado del Módulo -->
                                <div>
                                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                        Estado del Módulo <span class="text-red-500">*</span>
                                    </label>
                                    <select id="estado" 
                                            name="estado" 
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('estado') border-red-300 @enderror"
                                            required>
                                        @foreach (\App\Models\Usuarios\Modulo::getEstados() as $estado)
                                            <option value="{{ $estado }}" {{ old('estado', $modulo->estado) == $estado ? 'selected' : '' }}>
                                                {{ ucfirst($estado) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estado')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    
                                    <!-- Estado actual visual -->
                                    <div class="mt-2 flex items-center">
                                        <span class="text-xs text-gray-500 mr-2">Estado actual:</span>
                                        @switch($modulo->estado)
                                            @case('activo')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-1.5 h-1.5 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3"/>
                                                    </svg>
                                                    Activo
                                                </span>
                                                @break
                                            @case('mantenimiento')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-1.5 h-1.5 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3"/>
                                                    </svg>
                                                    Mantenimiento
                                                </span>
                                                @break
                                            @case('inactivo')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-1.5 h-1.5 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3"/>
                                                    </svg>
                                                    Inactivo
                                                </span>
                                                @break
                                        @endswitch
                                    </div>
                                    
                                    <div class="mt-2 text-xs text-gray-500">
                                        <div class="space-y-1">
                                            <div><strong>Activo:</strong> El módulo está operativo y disponible</div>
                                            <div><strong>Mantenimiento:</strong> El módulo está en desarrollo o actualización</div>
                                            <div><strong>Inactivo:</strong> El módulo está deshabilitado temporalmente</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información sobre roles y permisos -->
                                @if($modulo->roles()->count() > 0 || $modulo->permisos()->count() > 0)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">
                                                Atención: Módulo con roles y permisos asignados
                                            </h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>Este módulo tiene <strong>{{ $modulo->roles()->count() }} roles</strong> y <strong>{{ $modulo->permisos()->count() }} permisos</strong> asociados. Los cambios pueden afectar el funcionamiento del sistema.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

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
                                                Gestión de roles y permisos
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>Para gestionar los roles y permisos de este módulo, utilice la <a href="{{ route('usuarios.modulos.show', $modulo) }}" class="font-medium underline">vista de gestión completa</a>.</p>
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