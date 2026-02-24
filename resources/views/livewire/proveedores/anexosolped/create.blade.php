<div>
    <x-page-header title="Anexo Solped - Solicitud de Pedido para SAP">
    </x-page-header>

    <div class="w-full max-w-6xl mx-auto">
        @if (session()->has('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form wire:submit.prevent="descargarPdf" class="space-y-8">
                
                <!-- Título -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        Información General
                    </h3>
                    
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                            Título *
                        </label>
                        <input type="text" 
                               wire:model.defer="titulo"
                               id="titulo"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Ingrese el título de la solicitud">
                        @error('titulo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Central -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        Central *
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($centrales_disponibles as $central)
                            <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" 
                                       wire:model.defer="centrales"
                                       value="{{ $central }}"
                                       class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $central }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('centrales')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Documentos Adjuntos -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        Documentos Adjuntos *
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($documentos_disponibles as $documento)
                            <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" 
                                       wire:model.defer="documentos_adjuntos"
                                       value="{{ $documento }}"
                                       class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $documento }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('documentos_adjuntos')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Configuración Técnica -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        Configuración Técnica
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Visita Técnica -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Visita Técnica *
                            </label>
                            <div class="space-y-2">
                                @foreach(['Si', 'No', 'Opcional'] as $opcion)
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" 
                                               wire:model.defer="visita_tecnica"
                                               value="{{ $opcion }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $opcion }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('visita_tecnica')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Garantía Técnica -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Garantía Técnica *
                            </label>
                            <div class="space-y-2">
                                @foreach(['Si', 'No'] as $opcion)
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" 
                                               wire:model.live="garantia_tecnica"
                                               value="{{ $opcion }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $opcion }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('garantia_tecnica')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Plazo de Garantía - Solo visible si garantia_tecnica es "Si" -->
                        @if($garantia_tecnica === 'Si')
                            <div class="md:col-span-2">
                                <label for="plazo_garantia" class="block text-sm font-medium text-gray-700 mb-2">
                                    Plazo de Garantía *
                                </label>
                                <input type="text" 
                                       wire:model.defer="plazo_garantia"
                                       id="plazo_garantia"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Ej: 12 meses, 24 meses, etc.">
                                @error('plazo_garantia')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Adjudicación Parcial -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Admite Adjudicación Parcial *
                            </label>
                            <div class="space-y-2">
                                @foreach(['Si', 'No', 'Consultar'] as $opcion)
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" 
                                               wire:model.defer="adjudicacion_parcial"
                                               value="{{ $opcion }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $opcion }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('adjudicacion_parcial')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Marcas Alternativas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Admite Marcas Alternativas *
                            </label>
                            <div class="space-y-2">
                                @foreach(['Si', 'No'] as $opcion)
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" 
                                               wire:model.defer="marcas_alternativas"
                                               value="{{ $opcion }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $opcion }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('marcas_alternativas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ingreso a Planta -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Requiere Ingreso a Planta *
                            </label>
                            <div class="space-y-2">
                                @foreach(['Si', 'No'] as $opcion)
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" 
                                               wire:model.live="ingreso_planta"
                                               value="{{ $opcion }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $opcion }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('ingreso_planta')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adquicisión de sustancias peligrosas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Anexo MASH Sustancias Peligrosas  *
                            </label>
                            <div class="space-y-2">
                                @foreach(['Si', 'No'] as $opcion)
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" 
                                               wire:model.live="sustancias_peligrosas"
                                               value="{{ $opcion }}"
                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">{{ $opcion }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('sustancias_peligrosas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            @if($ingreso_planta === 'Si' || $sustancias_peligrosas === 'Si' )
                                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">
                                                Documentación adicional requerida
                                            </h3>
                                            @if ($ingreso_planta === 'Si')    
                                            <p class="mt-1 text-sm text-yellow-700">
                                                Debe adjuntar el <strong>Anexo Requisitos de Ingreso a Planta</strong>
                                            </p>
                                            @endif
                                            @if ($sustancias_peligrosas === 'Si')    
                                            <p class="mt-1 text-sm text-yellow-700">
                                                Debe adjuntar el <strong>Anexo Adquisición y Transporte de Productos con Categoría Peligrosos</strong>
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contactos -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        Información de Contacto
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contacto Técnico -->
                        <div class="space-y-4 p-4 bg-gray-50 rounded-md">
                            <h4 class="text-sm font-semibold text-gray-900">Contacto Técnico *</h4>
                            
                            <div>
                                <label for="contacto_tecnico_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre *
                                </label>
                                <input type="text" 
                                       wire:model.defer="contacto_tecnico_nombre"
                                       id="contacto_tecnico_nombre"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Nombre del contacto">
                                @error('contacto_tecnico_nombre')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contacto_tecnico_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input type="email" 
                                       wire:model.defer="contacto_tecnico_email"
                                       id="contacto_tecnico_email"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="email@ejemplo.com">
                                @error('contacto_tecnico_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contacto_tecnico_tel" class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono *
                                </label>
                                <input type="tel" 
                                       wire:model.defer="contacto_tecnico_tel"
                                       id="contacto_tecnico_tel"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="+54 221 XXX-XXXX">
                                @error('contacto_tecnico_tel')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Contacto de Seguimiento -->
                        <div class="space-y-4 p-4 bg-gray-50 rounded-md">
                            <h4 class="text-sm font-semibold text-gray-900">Contacto de Seguimiento *</h4>
                            
                            <div>
                                <label for="contacto_seguimiento_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre *
                                </label>
                                <input type="text" 
                                       wire:model.defer="contacto_seguimiento_nombre"
                                       id="contacto_seguimiento_nombre"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Nombre del contacto">
                                @error('contacto_seguimiento_nombre')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contacto_seguimiento_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input type="email" 
                                       wire:model.defer="contacto_seguimiento_email"
                                       id="contacto_seguimiento_email"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="email@ejemplo.com">
                                @error('contacto_seguimiento_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contacto_seguimiento_tel" class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono *
                                </label>
                                <input type="tel" 
                                       wire:model.defer="contacto_seguimiento_tel"
                                       id="contacto_seguimiento_tel"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="+54 221 XXX-XXXX">
                                @error('contacto_seguimiento_tel')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logística -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        Información de Logística
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="plazo_entrega" class="block text-sm font-medium text-gray-700 mb-2">
                                Plazo de Entrega / Duración de Servicio *
                            </label>
                            <input type="text" 
                                   wire:model.defer="plazo_entrega"
                                   id="plazo_entrega"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Ej: 30 días corridos, 2 meses, etc.">
                            @error('plazo_entrega')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="a_partir_de" class="block text-sm font-medium text-gray-700 mb-2">
                                A partir de *
                            </label>
                            <input type="text" 
                                   wire:model.defer="a_partir_de"
                                   id="a_partir_de"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Ej: Orden de compra, Firma del contrato, etc.">
                            @error('a_partir_de')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="lugar_entrega" class="block text-sm font-medium text-gray-700 mb-2">
                                Lugar de Entrega / Lugar de Realización *
                            </label>
                            <input type="text" 
                                   wire:model.defer="lugar_entrega"
                                   id="lugar_entrega"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Dirección o ubicación específica">
                            @error('lugar_entrega')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Proveedores -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        Proveedores *
                    </h3>

                    <div class="flex items-center pb-2">
                        <div class="mr-auto">
                            <button type="button"
                                    wire:click="abrirModal"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Agregar Proveedor
                            </button>
                        </div>
                        <div class="ml-auto">
                            @if (session()->has('proveedor_agregado'))
                                <span class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded-md ml-4">
                                    {{ session('proveedor_agregado') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @error('proveedores_seleccionados')
                        <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if(count($proveedores_seleccionados) > 0)
                        <div class="bg-gray-50 rounded-md border border-gray-200 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Razón Social</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">CUIT</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Correo</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Teléfono</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($proveedores_seleccionados as $index => $proveedor)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                {{ $proveedor['razonsocial'] }}
                                                @if($proveedor['tipo'] === 'manual')
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        Manual
                                                    </span>
                                                @else
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        ID: {{$proveedor['id']}}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $proveedor['cuit'] }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $proveedor['correo'] }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">{{ $proveedor['telefono'] }}</td>
                                            <td class="px-4 py-3 text-sm text-center">
                                                <button type="button"
                                                        wire:click="eliminarProveedor({{ $index }})"
                                                        class="text-red-600 hover:text-red-800 font-medium">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-center text-sm text-gray-500">
                            No hay proveedores agregados. Haga clic en "Agregar Proveedor" para comenzar.
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rubro</label>
                        <select wire:model.live="selectedRubro" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione un rubro</option>
                            @foreach($rubros as $rubro)
                                <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subrubro</label>
                        <select wire:model.live="selectedSubrubro" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                {{ empty($subrubros) ? 'disabled' : '' }}>
                            <option value="">Seleccione un subrubro</option>
                            @foreach($subrubros as $subrubro)
                                <option value="{{ $subrubro->id }}">{{ $subrubro->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Observaciones -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">
                        Observaciones
                    </h3>
                    
                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                            Observaciones adicionales
                        </label>
                        <textarea wire:model.defer="observaciones"
                                  id="observaciones"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Ingrese cualquier observación o detalle adicional que considere necesario..."></textarea>
                    </div>
                </div>

                <!-- Botón de descarga -->
                @if ($errors->any())
                <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">
                                Atención: No se puede generar el PDF
                            </h3>
                            <p class="text-sm text-red-700 mt-1">
                                Hay campos obligatorios vacíos o con errores en el formulario. Por favor, revise las secciones marcadas en rojo.
                            </p>
                            <ul class="mt-2 list-disc list-inside text-xs text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-end items-center pt-6 border-t border-gray-200">
                <div wire:loading wire:target="descargarPdf" class="mr-4 text-sm text-gray-500">
                    Generando documento...
                </div>
                
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="px-6 py-3 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors inline-flex items-center disabled:opacity-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Descargar PDF
                </button>
            </div>
            </form>
        </div>
    </div>
    @if($modal_abierto)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="cerrarModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                                Agregar Proveedor
                            </h3>
                            <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button type="button"
                                        wire:click="cambiarModoModal('buscar')"
                                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $modo_modal === 'buscar' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Buscar Existente
                                </button>
                                <button type="button"
                                        wire:click="cambiarModoModal('manual')"
                                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $modo_modal === 'manual' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Carga Manual
                                </button>
                            </nav>
                        </div>

                        @if($modo_modal === 'buscar')
                            <div class="space-y-4">
                                <div>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="buscar_proveedor"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Buscar por Razón Social o CUIT...">
                                </div>

                                <div class="overflow-hidden border border-gray-200 rounded-md">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Razón Social</th>
                                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">CUIT</th>
                                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($this->proveedoresDisponibles as $prov)
                                                <tr class="hover:bg-blue-50 transition-colors">
                                                    <td class="px-4 py-2 text-sm text-gray-900 font-medium">{{ $prov->razonsocial }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $prov->cuit }}</td>
                                                    <td class="px-4 py-2 text-right">
                                                        <button type="button"
                                                                wire:click="agregarProveedorExistente({{ $prov->id }})"
                                                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded hover:bg-blue-200 transition-colors">
                                                            Seleccionar
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="px-4 py-4 text-sm text-center text-gray-500">
                                                        No se encontraron proveedores.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if($modo_modal === 'manual')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Razón Social *</label>
                                    <input type="text" wire:model="proveedor_manual.razonsocial" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    @error('proveedor_manual.razonsocial') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">CUIT</label>
                                    <input type="text" wire:model="proveedor_manual.cuit" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                                    <input type="email" wire:model="proveedor_manual.correo" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    @error('proveedor_manual.correo') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Teléfono *</label>
                                    <input type="text" wire:model="proveedor_manual.telefono" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    @error('proveedor_manual.telefono') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" 
                                        wire:click="agregarProveedorManual"
                                        class="px-4 py-2 bg-green-600 text-white font-bold rounded-md hover:bg-green-700 transition-colors shadow-sm">
                                    Agregar Manualmente
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>