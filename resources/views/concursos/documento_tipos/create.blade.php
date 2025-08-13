<x-app-layout>
    <x-page-header title="Crear Nuevo Tipo de Documento">
        <x-slot:actions>
            <button type="submit" form="create-form" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                Guardar
            </button>
            <a href="{{ route('concursos.documento_tipos.index') }}" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-md transition-colors">
                Volver
            </a>
        </x-slot:actions>
    </x-page-header>
    
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form id="create-form" action="{{ route('concursos.documento_tipos.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Columna principal -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Tipo de Documento</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Tipo *</label>
                                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                               placeholder="Ej: Factura, Presupuesto, etc." required>
                                        @error('nombre')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                        <textarea id="descripcion" name="descripcion" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                                  placeholder="Describa el propósito de este tipo de documento">{{ old('descripcion') }}</textarea>
                                        @error('descripcion')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="de_concurso" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Uso *</label>
                                        <select id="de_concurso" name="de_concurso" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="1">Para Concursos</option>
                                            <option value="0">Para Ofertas</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">Seleccione si este tipo de documento se usará en concursos o en ofertas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Columna lateral -->
                        <div class="space-y-6">
                            <!-- Campos solo para ofertas -->
                            <div id="campos-ofertas">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Configuración para Ofertas</h3>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="text-center mb-4">
                                        <svg class="h-8 w-8 text-yellow-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-sm font-medium text-yellow-800">Configuración Específica para Ofertas</p>
                                        <p class="text-xs text-yellow-700 mt-1">Las siguientes opciones solo se utilizan para documentos de ofertas</p>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        
                                        <div>
                                            <label for="obligatorio" class="block text-sm font-medium text-gray-700 mb-1">Obligatorio en Ofertas</label>
                                            <select id="obligatorio" name="obligatorio" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                <option value="0">No</option>
                                                <option value="1">Sí</option>
                                            </select>
                                            <p class="mt-1 text-xs text-gray-500">Los documentos obligatorios deben ser subidos para completar la oferta</p>
                                        </div>
                                        
                                        <div>
                                            <label for="tipo_documento_proveedor_id" class="block text-sm font-medium text-gray-700 mb-1">Asociado a Documento de Proveedor</label>
                                            <select id="tipo_documento_proveedor_id" name="tipo_documento_proveedor_id" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                <option value="0">Sin Asociación</option>
                                                @foreach ($tipo_documento_proveedor as $tipo_documento)
                                                    <option value="{{ $tipo_documento->id }}">{{ $tipo_documento->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <p class="mt-1 text-xs text-gray-500">Permite vincular con documentos del sistema de proveedores</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Información adicional -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800">Tipos de Documentos</h4>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>Los tipos de documentos definen qué archivos pueden subir los proveedores en concursos y ofertas.</p>
                                            <p class="mt-1">Configure las opciones según las necesidades específicas de su organización.</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deConcursoSelect = document.getElementById('de_concurso');
            const camposOfertas = document.getElementById('campos-ofertas');

            function toggleCamposOfertas() {
                if (deConcursoSelect.value === '1') {
                    // Para Concursos - ocultar campos
                    camposOfertas.style.display = 'none';
                } else {
                    // Para Ofertas - mostrar campos
                    camposOfertas.style.display = 'block';
                }
            }

            // Ejecutar al cargar la página
            toggleCamposOfertas();

            // Ejecutar cuando cambie el select
            deConcursoSelect.addEventListener('change', toggleCamposOfertas);
        });
    </script>
</x-app-layout>
