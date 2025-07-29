<x-app-layout>
    <!-- Header -->
    <x-page-header title="Editar Proveedor" />
    
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('proveedores.proveedors.update', $proveedor) }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    @method('put')
                    
                    <!-- Header del formulario -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">
                                Datos del Proveedor: {{ $proveedor->razonsocial }}
                            </h3>
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                        :disabled="loading"
                                        :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                    <span x-show="!loading">Guardar Cambios</span>
                                    <span x-show="loading" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Guardando...
                                    </span>
                                </button>
                                <a href="{{ route('proveedores.proveedors.show', $proveedor) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Volver
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
                                    @cannot('Proveedores/Proveedores/Editar')
                                        <p class="text-xs text-gray-500 mt-1">Solo lectura</p>
                                    @endcannot
                                </div>
                                
                                <!-- Razón Social -->
                                <div>
                                    <label for="razonsocial" class="block text-sm font-medium text-gray-700 mb-2">
                                        Razón Social 
                                        @can('Proveedores/Proveedores/Editar')
                                            <span class="text-red-500">*</span>
                                        @endcan
                                    </label>
                                    <input type="text" 
                                           id="razonsocial"
                                           name="razonsocial" 
                                           value="{{ $proveedor->razonsocial }}" 
                                           class="block w-full px-3 py-2 border rounded-md shadow-sm sm:text-sm @can('Proveedores/Proveedores/Editar') border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('razonsocial') border-red-300 @enderror @else border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed @endcan" 
                                           placeholder="Ingrese la razón social"
                                           @cannot('Proveedores/Proveedores/Editar') disabled @endcannot
                                           @can('Proveedores/Proveedores/Editar') required @endcan
                                           autocomplete="off">
                                    @can('Proveedores/Proveedores/Editar')
                                        @error('razonsocial')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    @endcan
                                </div>

                                <!-- CUIT -->
                                <div>
                                    <label for="cuit" class="block text-sm font-medium text-gray-700 mb-2">
                                        CUIT 
                                        @can('Proveedores/Proveedores/Editar')
                                            <span class="text-red-500">*</span>
                                        @endcan
                                    </label>
                                    <input type="text" 
                                           id="cuit"
                                           name="cuit" 
                                           value="{{ $proveedor->cuit }}" 
                                           class="block w-full px-3 py-2 border rounded-md shadow-sm sm:text-sm @can('Proveedores/Proveedores/Editar') border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('cuit') border-red-300 @enderror @else border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed @endcan" 
                                           placeholder="XX-XXXXXXXX-X"
                                           @cannot('Proveedores/Proveedores/Editar') disabled @endcannot
                                           @can('Proveedores/Proveedores/Editar') required @endcan
                                           autocomplete="off">
                                    @can('Proveedores/Proveedores/Editar')
                                        @error('cuit')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    @endcan
                                </div>

                                <!-- Correo Institucional -->
                                <div>
                                    <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo Institucional 
                                        @can('Proveedores/Proveedores/Editar')
                                            <span class="text-red-500">*</span>
                                        @endcan
                                    </label>
                                    <input type="email" 
                                           id="correo"
                                           name="correo" 
                                           value="{{ $proveedor->correo }}" 
                                           class="block w-full px-3 py-2 border rounded-md shadow-sm sm:text-sm @can('Proveedores/Proveedores/Editar') border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('correo') border-red-300 @enderror @else border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed @endcan" 
                                           placeholder="contacto@empresa.com"
                                           @cannot('Proveedores/Proveedores/Editar') disabled @endcannot
                                           @can('Proveedores/Proveedores/Editar') required @endcan
                                           autocomplete="off">
                                    @can('Proveedores/Proveedores/Editar')
                                        @error('correo')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    @endcan
                                </div>

                                <!-- Nombre de Fantasía -->
                                @can('Proveedores/Proveedores/Editar')
                                <div>
                                    <label for="fantasia" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre de Fantasía
                                    </label>
                                    <input type="text" 
                                           id="fantasia"
                                           name="fantasia" 
                                           value="{{ $proveedor->fantasia }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('fantasia') border-red-300 @enderror" 
                                           placeholder="Nombre comercial"
                                           autocomplete="off">
                                    @error('fantasia')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endcan
                            </div>

                            <!-- Columna derecha -->
                            <div class="space-y-6">
                                @can('Proveedores/Proveedores/Editar')
                                    <!-- Datos de contacto para usuarios con permiso de edición -->
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
                                               value="{{ $proveedor->telefono }}" 
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
                                               value="{{ $proveedor->webpage }}" 
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
                                               value="{{ $proveedor->horario }}" 
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('horario') border-red-300 @enderror" 
                                               placeholder="Lunes a Viernes 9:00 - 18:00"
                                               autocomplete="off">
                                        @error('horario')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endcan

                                @can('Proveedores/Proveedores/EditarEstado')
                                    <!-- Gestión de estado -->
                                    <div class="@can('Proveedores/Proveedores/Editar') mt-8 pt-6 border-t border-gray-200 @else border-b border-gray-200 pb-4 @endcan">
                                        <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Gestión de Estado</h4>
                                    </div>

                                    <!-- Nivel del Proveedor -->
                                    <div>
                                        <label for="estado_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nivel del Proveedor <span class="text-red-500">*</span>
                                        </label>
                                        <select name="estado_id" 
                                                id="estado_id"
                                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            @foreach (\App\Models\Proveedores\Estado::all() as $estado)
                                                <option value="{{ $estado->id }}" @selected($estado->id == $proveedor->estado->id)>
                                                    {{ $estado->estado }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Proveedor en Litigio -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                            Estado de Litigio
                                        </label>
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   id="litigio"
                                                   name="litigio" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                                   @checked($proveedor->litigio)>
                                            <label for="litigio" class="ml-2 block text-sm text-gray-900">
                                                Proveedor en Litigio
                                            </label>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Marque esta opción si el proveedor tiene conflictos legales pendientes</p>
                                    </div>

                                    <!-- Estado actual -->
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                        <div class="flex">
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">
                                                    Estado actual
                                                </h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <p><strong>Nivel:</strong> {{ $proveedor->estado->estado }}</p>
                                                    <p><strong>En litigio:</strong> {{ $proveedor->litigio ? 'Sí' : 'No' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>