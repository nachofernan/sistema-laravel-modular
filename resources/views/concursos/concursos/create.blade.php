<x-app-layout>
    <x-page-header title="Crear Nuevo Concurso">
        <x-slot:actions>
            <button type="submit" form="create-form" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                Guardar
            </button>
            <a href="{{ route('concursos.concursos.index') }}" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-md transition-colors">
                Volver
            </a>
        </x-slot:actions>
    </x-page-header>
    
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form id="create-form" action="{{ route('concursos.concursos.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Columna principal -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Concurso</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Concurso *</label>
                                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                               placeholder="Ingrese el nombre del concurso" required autocomplete="off">
                                        @error('nombre')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                                        <textarea id="descripcion" name="descripcion" rows="4" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                                  placeholder="Describa los detalles del concurso" required>{{ old('descripcion') }}</textarea>
                                        @error('descripcion')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de Legajo *</label>
                                        @livewire('concursos.concurso.create.legajo-input', ['numero_legajo' => old('numero_legajo')])
                                    </div>
                                    
                                    <div>
                                        <label for="legajo" class="block text-sm font-medium text-gray-700 mb-1">Link al Legajo *</label>
                                        <input type="text" id="legajo" name="legajo" value="{{ old('legajo') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                               placeholder="https://ejemplo.com/legajo" required autocomplete="off">
                                        @error('legajo')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Fechas del Concurso</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio *</label>
                                        <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" 
                                               value="{{ old('fecha_inicio') ?? now()->format('Y-m-d H:i') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                               required autocomplete="off">
                                        @error('fecha_inicio')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="fecha_cierre" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Cierre *</label>
                                        <input type="datetime-local" id="fecha_cierre" name="fecha_cierre" 
                                               value="{{ old('fecha_cierre') ?? now()->addDays(14)->format('Y-m-d H:i') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                               required autocomplete="off" min="{{ now()->format('Y-m-d H:i') }}">
                                        @error('fecha_cierre')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Columna lateral -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Sedes Participantes</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 mb-3">Seleccione las sedes que participarán en este concurso:</p>
                                    <div class="space-y-3">
                                        @foreach($sedes as $sede)
                                        <div class="flex items-center">
                                            <input type="checkbox" id="sede_{{ $sede->id }}" name="sedes[]" value="{{ $sede->id }}" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                   {{ in_array($sede->id, old('sedes', [])) ? 'checked' : '' }}>
                                            <label for="sede_{{ $sede->id }}" class="ml-3 text-sm text-gray-700">{{ $sede->nombre }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('sedes')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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
                                        <h4 class="text-sm font-medium text-blue-800">Información Importante</h4>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>El número de legajo debe ser único</li>
                                                <li>La fecha de cierre debe ser posterior a la fecha de inicio</li>
                                                <li>Al menos una sede debe ser seleccionada</li>
                                                <li>Después de crear el concurso podrá invitar proveedores</li>
                                            </ul>
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
</x-app-layout>