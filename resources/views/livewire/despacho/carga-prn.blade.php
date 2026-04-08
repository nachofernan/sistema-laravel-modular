<div class="p-4">
    {{-- The Master doesn't talk, he acts. --}}

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-800">Carga de archivo PRN</h1>
    </div>

    <div class="max-w-2xl">

        {{-- ── Selección de registrador ────────────────────────── --}}
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-600 mb-1">Registrador *</label>
            <select wire:model.live="registrador_id"
                    class="w-full border rounded px-2 py-1.5 text-sm @error('registrador_id') border-red-500 @enderror">
                <option value="">— Seleccioná un registrador —</option>
                @foreach($registradores as $reg)
                    <option value="{{ $reg->id }}">{{ $reg->codigo }}{{ $reg->nombre ? ' — ' . $reg->nombre : '' }}</option>
                @endforeach
            </select>
            @error('registrador_id')
                <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Info del registrador seleccionado ──────────────── --}}
        @if($infoRegistrador)
        <div class="mb-4 p-3 bg-gray-100 border border-gray-300 rounded text-xs font-mono">
            <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                <div><span class="text-gray-500">Máquinas:</span> {{ $infoRegistrador['maquinas'] }}</div>
                <div><span class="text-gray-500">Registrador:</span> {{ $infoRegistrador['codigo'] }}</div>
                <div><span class="text-gray-500">Tipo de dato:</span> {{ ucfirst($infoRegistrador['tipo_dato']) }}</div>
                <div><span class="text-gray-500">Columna a leer:</span> {{ $infoRegistrador['columna_datos'] }}</div>
                <div><span class="text-gray-500">Factor de conversión:</span> {{ $infoRegistrador['factor_conversion'] }}</div>
            </div>
        </div>
        @endif

        {{-- ── Upload de archivo ───────────────────────────────── --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">Archivo PRN *</label>
            <input wire:model="archivo" type="file" accept=".prn,.txt,.csv"
                   class="w-full text-sm text-gray-600
                          file:mr-3 file:py-1.5 file:px-3
                          file:rounded file:border-0
                          file:text-sm file:bg-gray-200
                          file:hover:bg-gray-300
                          @error('archivo') border border-red-500 rounded @enderror">
            @error('archivo')
                <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Botón procesar / Spinner ─────────────────────────── --}}
        <div class="mb-4">
            <button wire:click="procesar"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50 flex items-center gap-2">

                {{-- Spinner --}}
                <span wire:loading wire:target="procesar">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </span>

                <span wire:loading.remove wire:target="procesar">Procesar archivo</span>
                <span wire:loading wire:target="procesar">Procesando...</span>
            </button>
        </div>

        {{-- ── Resultado ────────────────────────────────────────── --}}
        @if($resultadoMsg)
        <div class="mb-4 p-3 rounded border text-sm
            {{ $resultadoTipo === 'success' ? 'bg-green-50 border-green-300 text-green-800' : '' }}
            {{ $resultadoTipo === 'warning' ? 'bg-yellow-50 border-yellow-300 text-yellow-800' : '' }}
            {{ $resultadoTipo === 'error'   ? 'bg-red-50 border-red-300 text-red-800' : '' }}">

            <p class="font-semibold mb-1">{{ $resultadoMsg }}</p>

            <div class="grid grid-cols-3 gap-2 text-xs mt-2">
                <div class="bg-white rounded p-2 border text-center">
                    <div class="text-lg font-bold text-green-700">{{ $insertados }}</div>
                    <div class="text-gray-500">Insertados</div>
                </div>
                <div class="bg-white rounded p-2 border text-center">
                    <div class="text-lg font-bold text-yellow-600">{{ $duplicados }}</div>
                    <div class="text-gray-500">Duplicados ignorados</div>
                </div>
                <div class="bg-white rounded p-2 border text-center">
                    <div class="text-lg font-bold text-red-600">{{ $errorCount }}</div>
                    <div class="text-gray-500">Errores</div>
                </div>
            </div>

            {{-- Detalle de errores de línea --}}
            @if(count($erroresLinea) > 0)
            <div class="mt-3">
                <p class="text-xs font-semibold mb-1 text-gray-600">Detalle de errores
                    @if($errorCount > 50)(mostrando primeros 50)@endif:
                </p>
                <div class="bg-white border rounded p-2 max-h-40 overflow-y-auto">
                    @foreach($erroresLinea as $err)
                        <p class="text-xs font-mono text-red-700">{{ $err }}</p>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
        @endif

    </div>
</div>