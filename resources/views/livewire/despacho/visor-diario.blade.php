<div class="p-4">

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-800">Visor diario</h1>
        <button wire:click="abrirCalculadora"
                class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium bg-indigo-600 text-white rounded hover:bg-indigo-700">
            🧮 Calculadora
        </button>
    </div>

    {{-- ── Filtros ─────────────────────────────────────────────── --}}
    <div class="flex gap-4 mb-5 items-end">

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Máquina</label>
            <select wire:model.live="maquina_id"
                    class="border rounded px-2 py-1.5 text-sm min-w-48">
                <option value="">— Seleccioná —</option>
                @foreach($maquinas as $maquina)
                    <option value="{{ $maquina->id }}">{{ $maquina->codigo }} — {{ $maquina->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Fecha</label>
            <input wire:model.live="fecha" type="date"
                   class="border rounded px-2 py-1.5 text-sm">
        </div>

    </div>

    {{-- ── Tabla ───────────────────────────────────────────────── --}}
    @if($maquina_id && $fecha && $registradores->isNotEmpty() && count($bloques) > 0)

        {{-- Leyenda --}}
        <div class="flex gap-4 mb-3 text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <span class="inline-block w-3 h-3 rounded-sm bg-red-200 border border-red-400"></span> Sin datos
            </span>
            <span class="flex items-center gap-1">
                <span class="inline-block w-3 h-3 rounded-sm bg-yellow-100 border border-yellow-400"></span> Incompleto
            </span>
            <span class="flex items-center gap-1">
                <span class="inline-block w-3 h-3 rounded-sm bg-white border border-gray-300"></span> Completo
            </span>
        </div>

        <table class="w-full text-sm border-collapse">
            <thead>
                {{-- Fila de tipos --}}
                <tr class="bg-gray-200 text-xs text-gray-500 uppercase text-center">
                    <th class="px-3 py-1 border w-8"></th>
                    <th class="px-3 py-1 border text-left">Hora</th>
                    @foreach($registradores as $reg)
                        <th class="px-3 py-1 border">
                            <span class="
                                @if($reg->tipo === 'principal') text-blue-700
                                @elseif($reg->tipo === 'respaldo') text-green-700
                                @elseif($reg->tipo === 'control') text-purple-700
                                @else text-gray-500
                                @endif
                            ">{{ ucfirst($reg->tipo) }}</span>
                        </th>
                    @endforeach
                </tr>
                {{-- Fila de códigos --}}
                <tr class="bg-gray-100 text-xs text-gray-600 text-center">
                    <th class="border"></th>
                    <th class="border"></th>
                    @foreach($registradores as $reg)
                        <th class="px-3 py-1.5 border font-mono font-semibold">{{ $reg->codigo }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($bloques as $bloque)

                    {{-- Fila de bloque horario --}}
                    <tr wire:click="toggleBloque({{ $bloque['bloque'] }})"
                        class="cursor-pointer border-b bg-white hover:bg-gray-50">

                        <td class="px-3 py-2 border text-center text-gray-400 text-xs select-none">
                            @php $tieneDatos = collect($bloque['columnas'])->contains(fn($c) => $c['total_cuartos'] > 0); @endphp
                            @if($tieneDatos)
                                {{ $bloque['expandido'] ? '▲' : '▼' }}
                            @endif
                        </td>

                        <td class="px-3 py-2 border font-mono font-semibold">
                            {{ $bloque['label'] }}
                        </td>

                        @foreach($registradores as $reg)
                            @php $col = $bloque['columnas'][$reg->id]; @endphp
                            <td class="px-3 py-2 border text-right font-mono
                                {{ $col['estado'] === 'vacio'      ? 'bg-red-50' : '' }}
                                {{ $col['estado'] === 'incompleto' ? 'bg-yellow-50' : '' }}">
                                @if($col['estado'] === 'vacio')
                                    <span class="text-red-300 text-xs">—</span>
                                @else
                                    <span class="{{ $col['estado'] === 'incompleto' ? 'text-yellow-600' : '' }}">
                                        {{ number_format($col['suma_convertida'], 2, ',', '.') }}
                                    </span>
                                    @if($col['total_cuartos'] < 4)
                                        <span class="text-yellow-500 text-xs ml-1">({{ $col['total_cuartos'] }}/4)</span>
                                    @endif
                                @endif
                            </td>
                        @endforeach

                    </tr>

                    {{-- Filas de cuartos (expandido) --}}
                    @if($bloque['expandido'] && $tieneDatos)
                        @php
                            // Máximo de cuartos entre todos los registradores para este bloque
                            $maxCuartos = collect($bloque['columnas'])->max(fn($c) => $c['cuartos']->count());
                        @endphp
                        @for($q = 0; $q < $maxCuartos; $q++)
                        <tr class="bg-gray-50 text-xs border-b">
                            <td class="border"></td>
                            <td class="px-5 py-1.5 border font-mono text-gray-400">
                                {{-- Hora del cuarto: tomamos del primer registrador que tenga ese índice --}}
                                @php
                                    $cuartoRef = null;
                                    foreach ($bloque['columnas'] as $col) {
                                        if (isset($col['cuartos'][$q])) {
                                            $cuartoRef = $col['cuartos'][$q];
                                            break;
                                        }
                                    }
                                @endphp
                                @if($cuartoRef)
                                    {{ substr($cuartoRef->hora_desde, 0, 5) }} → {{ substr($cuartoRef->hora_hasta, 0, 5) }}
                                @endif
                            </td>
                            @foreach($registradores as $reg)
                                @php $cuarto = $bloque['columnas'][$reg->id]['cuartos'][$q] ?? null; @endphp
                                <td class="px-3 py-1.5 border text-right font-mono">
                                    @if($cuarto)
                                        <span class="text-gray-700">{{ number_format($cuarto->valor_convertido, 4, ',', '.') }}</span>
                                        <span class="text-gray-400 ml-1">({{ number_format($cuarto->valor_crudo, 0, ',', '.') }})</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endfor
                    @endif

                @endforeach
            </tbody>

            {{-- Totales del día --}}
            <tfoot>
                <tr class="bg-gray-100 font-semibold text-sm">
                    <td class="border"></td>
                    <td class="px-3 py-2 border">Total del día</td>
                    @foreach($registradores as $reg)
                        @php
                            $totalReg = collect($bloques)->sum(fn($b) => $b['columnas'][$reg->id]['suma_convertida']);
                        @endphp
                        <td class="px-3 py-2 border text-right font-mono">
                            {{ number_format($totalReg, 2, ',', '.') }}
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>

    @elseif($maquina_id && $fecha && $registradores->isEmpty())
        <p class="text-sm text-gray-400 italic">La máquina no tiene registradores activos asignados.</p>

    @elseif($maquina_id && $fecha)
        <p class="text-sm text-gray-400 italic">No hay datos para esa fecha.</p>

    @else
        <p class="text-sm text-gray-400 italic">Seleccioná una máquina y una fecha para ver los datos.</p>
    @endif

    {{-- ── Modal Calculadora ──────────────────────────────────── --}}
    @if($modalCalculadora)
    <div class="fixed inset-0 z-50 flex items-center justify-center">

        {{-- Backdrop --}}
        <div wire:click="cerrarCalculadora" class="absolute inset-0 bg-black/40"></div>

        {{-- Panel --}}
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6 z-10">

            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800">Calculadora de energía</h2>
                <button wire:click="cerrarCalculadora" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>

            <div class="space-y-4">

                {{-- Registrador --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Registrador</label>
                    <select wire:model.live="calc_registrador_id"
                            class="w-full border rounded px-2 py-1.5 text-sm @error('calc_registrador_id') border-red-400 @enderror">
                        <option value="">— Seleccioná —</option>
                        @foreach($maquinasConRegistradores as $maq)
                            <optgroup label="{{ $maq->codigo }} — {{ $maq->nombre }}">
                                @foreach($maq->registradores as $reg)
                                    <option value="{{ $reg->id }}">
                                        {{ $reg->codigo }}{{ $reg->nombre ? ' — ' . $reg->nombre : '' }} ({{ $reg->tipo }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('calc_registrador_id') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                </div>

                {{-- Desde / Hasta --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Desde</label>
                        <input wire:model.live="calc_fecha_desde" type="date"
                               class="w-full border rounded px-2 py-1.5 text-sm @error('calc_fecha_desde') border-red-400 @enderror">
                        @error('calc_fecha_desde') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        <input wire:model.live="calc_hora_desde" type="time" step="900"
                               class="w-full border rounded px-2 py-1.5 text-sm mt-1.5 @error('calc_hora_desde') border-red-400 @enderror">
                        @error('calc_hora_desde') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hasta</label>
                        <input wire:model.live="calc_fecha_hasta" type="date"
                               class="w-full border rounded px-2 py-1.5 text-sm @error('calc_fecha_hasta') border-red-400 @enderror">
                        @error('calc_fecha_hasta') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                        <input wire:model.live="calc_hora_hasta" type="time" step="900"
                               class="w-full border rounded px-2 py-1.5 text-sm mt-1.5 @error('calc_hora_hasta') border-red-400 @enderror">
                        @error('calc_hora_hasta') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Precio por mega --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Precio por mega</label>
                    <input wire:model.live="calc_precio" type="number" step="0.0001" min="0"
                           class="w-full border rounded px-2 py-1.5 text-sm font-mono @error('calc_precio') border-red-400 @enderror"
                           placeholder="1,0000">
                    @error('calc_precio') <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p> @enderror
                </div>

                {{-- Botón calcular --}}
                <button wire:click="calcular"
                        class="w-full py-2 bg-indigo-600 text-white rounded font-medium hover:bg-indigo-700 text-sm">
                    Calcular
                </button>

                {{-- Resultado --}}
                @if($calc_resultado !== null)
                <div class="mt-2 rounded-lg bg-gray-50 border border-gray-200 p-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Lecturas encontradas</span>
                        <span class="font-mono font-semibold text-gray-700">{{ $calc_resultado['cantidad'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total MWh</span>
                        <span class="font-mono font-semibold text-gray-800">{{ number_format($calc_resultado['total'], 4, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700 font-medium">Total × precio</span>
                        <span class="font-mono font-bold text-indigo-700 text-base">{{ number_format($calc_resultado['total_precio'], 4, ',', '.') }}</span>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endif

</div>