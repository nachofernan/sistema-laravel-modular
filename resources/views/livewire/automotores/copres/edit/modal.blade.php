<div>
    @if($showModal)
        <!-- Modal backdrop -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="modal-backdrop">
            <!-- Modal content -->
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900">Editar COPRES</h3>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Fecha -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha *</label>
                                <input type="date" wire:model="fecha" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('fecha') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Vehículo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehículo *</label>
                                <select wire:model="vehiculo_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Seleccionar vehículo</option>
                                    @foreach($vehiculos as $vehiculo)
                                        <option value="{{ $vehiculo->id }}">{{ $vehiculo->marca }} {{ $vehiculo->modelo }} - {{ $vehiculo->patente }}</option>
                                    @endforeach
                                </select>
                                @error('vehiculo_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Ticket/Factura -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ticket/Factura</label>
                                <input type="text" wire:model="numero_ticket_factura" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('numero_ticket_factura') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- CUIT -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CUIT</label>
                                <input type="text" wire:model="cuit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('cuit') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Litros -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Litros</label>
                                <input type="number" step="0.01" wire:model="litros" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('litros') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Precio por Litro -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio/Litro</label>
                                <input type="number" step="0.01" wire:model="precio_x_litro" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('precio_x_litro') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Importe Final -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Importe *</label>
                                <input type="number" step="0.01" wire:model="importe_final" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('importe_final') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- KM -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">KM</label>
                                <input type="number" wire:model="km_vehiculo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('km_vehiculo') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- KZ -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">KZ (Referencia SAP)</label>
                                <input type="number" wire:model="kz" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('kz') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Fecha de Salida -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Salida</label>
                                <input type="date" wire:model="salida" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('salida') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <!-- Fecha de Reentrada -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Reentrada</label>
                                <input type="date" wire:model="reentrada" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('reentrada') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>


                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
