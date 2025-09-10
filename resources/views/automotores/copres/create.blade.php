<x-app-layout>
    <x-page-header title="Registrar Nueva COPRES">
        <x-slot:actions>
            <a href="{{ route('automotores.copres.index') }}"
                class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-6xl mx-auto">
        <form action="{{ route('automotores.copres.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-6">
                    <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900">Datos de la COPRES</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Fecha -->
                    <div>
                        <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha *
                        </label>
                        <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('fecha') border-red-500 @enderror"
                            required>
                        @error('fecha')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vehículo -->
                    <div>
                        <label for="vehiculo_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Vehículo *
                        </label>
                        <select name="vehiculo_id" id="vehiculo_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('vehiculo_id') border-red-500 @enderror"
                            required>
                            <option value="">Seleccionar vehículo</option>
                            @foreach ($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}"
                                    {{ old('vehiculo_id', request('vehiculo_id')) == $vehiculo->id ? 'selected' : '' }}>
                                    {{ $vehiculo->patente }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                    ({{ number_format($vehiculo->kilometraje) }} km)
                                </option>
                            @endforeach
                        </select>
                        @error('vehiculo_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kilometraje del Vehículo -->
                    <div>
                        <label for="km_vehiculo" class="block text-sm font-medium text-gray-700 mb-2">
                            Kilometraje del Vehículo
                        </label>
                        <input type="number" name="km_vehiculo" id="km_vehiculo" min="0"
                            value="{{ old('km_vehiculo') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('km_vehiculo') border-red-500 @enderror"
                            placeholder="{{ $vehiculo->kilometraje }}">
                        @error('km_vehiculo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Número de Ticket/Factura -->
                    <div>
                        <label for="numero_ticket_factura" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Ticket/Factura
                        </label>
                        <input type="text" name="numero_ticket_factura" id="numero_ticket_factura"
                            value="{{ old('numero_ticket_factura') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('numero_ticket_factura') border-red-500 @enderror"
                            placeholder="Ej: 001-00000001" maxlength="13">
                        @error('numero_ticket_factura')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CUIT -->
                    <div>
                        <label for="cuit" class="block text-sm font-medium text-gray-700 mb-2">
                            CUIT
                        </label>
                        <input type="text" name="cuit" id="cuit" value="{{ old('cuit') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('cuit') border-red-500 @enderror"
                            placeholder="Ej: 20-12345678-9" maxlength="13">
                        @error('cuit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Documento -->
                    <div>
                        <label for="es_original" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Documento
                        </label>
                        <select name="es_original" id="es_original"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('es_original') border-red-500 @enderror">
                            <option value="1" {{ old('es_original', '1') == '1' ? 'selected' : '' }}>Original
                            </option>
                            <option value="0" {{ old('es_original') == '0' ? 'selected' : '' }}>Copia</option>
                        </select>
                        @error('es_original')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Litros -->
                    <div>
                        <label for="litros" class="block text-sm font-medium text-gray-700 mb-2">
                            Litros
                        </label>
                        <input type="number" name="litros" id="litros" step="0.01" min="0"
                            value="{{ old('litros') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('litros') border-red-500 @enderror"
                            placeholder="0.00">
                        @error('litros')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio por Litro -->
                    <div>
                        <label for="precio_x_litro" class="block text-sm font-medium text-gray-700 mb-2">
                            Precio por Litro
                        </label>
                        <input type="number" name="precio_x_litro" id="precio_x_litro" step="0.01" min="0"
                            value="{{ old('precio_x_litro') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('precio_x_litro') border-red-500 @enderror"
                            placeholder="0.00">
                        @error('precio_x_litro')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Importe Final -->
                    <div>
                        <label for="importe_final" class="block text-sm font-medium text-gray-700 mb-2">
                            Importe Final *
                        </label>
                        <input type="number" name="importe_final" id="importe_final" step="0.01" min="0"
                            value="{{ old('importe_final') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('importe_final') border-red-500 @enderror"
                            placeholder="0.00" required>
                        @error('importe_final')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Fecha de Salida -->
                    <div>
                        <label for="salida" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Salida
                        </label>
                        <input type="date" name="salida" id="salida" value="{{ old('salida') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('salida') border-red-500 @enderror">
                        @error('salida')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Reentrada -->
                    <div>
                        <label for="reentrada" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Reentrada
                        </label>
                        <input type="date" name="reentrada" id="reentrada" value="{{ old('reentrada') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('reentrada') border-red-500 @enderror">
                        @error('reentrada')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- KZ -->
                    <div>
                        <label for="kz" class="block text-sm font-medium text-gray-700 mb-2">
                            KZ (Referencia SAP)
                        </label>
                        <input type="number" name="kz" id="kz" value="{{ old('kz') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('kz') border-red-500 @enderror"
                            placeholder="Referencia SAP">
                        @error('kz')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end space-x-3 pt-3 mt-3 border-t border-gray-200">
                    <a href="{{ route('automotores.copres.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Registrar COPRES
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts para formateo automático -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Formateo del número de ticket/factura (XXXX-XXXXXXXX)
            const ticketInput = document.getElementById('numero_ticket_factura');
            if (ticketInput) {
                ticketInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, ''); // Solo números

                    if (value.length > 0) {
                        if (value.length <= 4) {
                            value = value;
                        } else {
                            value = value.substring(0, 4) + '-' + value.substring(4, 12);
                        }
                    }

                    e.target.value = value;
                });

                // Formatear valor inicial si existe
                if (ticketInput.value) {
                    let value = ticketInput.value.replace(/\D/g, '');
                    if (value.length > 4) {
                        ticketInput.value = value.substring(0, 4) + '-' + value.substring(4, 12);
                    }
                }
            }

            // Formateo del CUIT (XX-XXXXXXXX-X)
            const cuitInput = document.getElementById('cuit');
            if (cuitInput) {
                cuitInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, ''); // Solo números

                    if (value.length > 0) {
                        if (value.length <= 2) {
                            value = value;
                        } else if (value.length <= 10) {
                            value = value.substring(0, 2) + '-' + value.substring(2, 10);
                        } else {
                            value = value.substring(0, 2) + '-' + value.substring(2, 10) + '-' + value
                                .substring(10, 11);
                        }
                    }

                    e.target.value = value;
                });

                // Formatear valor inicial si existe
                if (cuitInput.value) {
                    let value = cuitInput.value.replace(/\D/g, '');
                    if (value.length > 2) {
                        if (value.length <= 10) {
                            cuitInput.value = value.substring(0, 2) + '-' + value.substring(2, 10);
                        } else {
                            cuitInput.value = value.substring(0, 2) + '-' + value.substring(2, 10) + '-' + value
                                .substring(10, 11);
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
