<x-app-layout>
    <x-page-header title="Crear Nueva Categoría de Inventario">
        <x-slot:actions>
            <button type="submit" form="create-form" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                Crear Categoría
            </button>
            <a href="{{ route('inventario.categorias.index') }}" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-md transition-colors">
                Cancelar
            </a>
        </x-slot:actions>
    </x-page-header>
    
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form id="create-form" action="{{ route('inventario.categorias.store') }}" method="POST">
                @csrf
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Categoría</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                           placeholder="Nombre de la categoría" required>
                                    @error('nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="prefijo" class="block text-sm font-medium text-gray-700 mb-1">Prefijo *</label>
                                    <input type="text" id="prefijo" name="prefijo" value="{{ old('prefijo') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                           placeholder="Prefijo para códigos" required maxlength="10">
                                    <p class="mt-1 text-xs text-gray-500">El prefijo se usa para generar códigos automáticos de elementos (máximo 10 caracteres)</p>
                                    @error('prefijo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información Adicional</h3>
                            <div class="space-y-4">
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-blue-800">¿Qué es una categoría?</h4>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>Las categorías permiten organizar y clasificar los elementos del inventario de manera lógica.</p>
                                                <p class="mt-1">Cada categoría puede tener características personalizadas y un prefijo único.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-green-800">Códigos Automáticos</h4>
                                            <div class="mt-2 text-sm text-green-700">
                                                <p>El prefijo se usará para generar códigos automáticos de elementos.</p>
                                                <p class="mt-1">Ejemplo: Si el prefijo es "COMP", los elementos tendrán códigos como "COMP001", "COMP002", etc.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-yellow-800">Próximos Pasos</h4>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>Después de crear la categoría, podrás:</p>
                                                <ul class="mt-1 list-disc list-inside space-y-1">
                                                    <li>Definir características específicas</li>
                                                    <li>Agregar elementos a esta categoría</li>
                                                    <li>Configurar opciones avanzadas</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Información adicional -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Categorías Existentes</h3>
                <div class="text-sm text-gray-600">
                    <p>Antes de crear una nueva categoría, considera revisar las categorías existentes para evitar duplicados.</p>
                    <div class="mt-3">
                        <a href="{{ route('inventario.categorias.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Ver Todas las Categorías
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 