<div class="p-4">

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-800">Visor diario</h1>
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
                                        {{ number_format($col['suma_convertida'], 2) }}
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
                                        <span class="text-gray-700">{{ number_format($cuarto->valor_convertido, 4) }}</span>
                                        <span class="text-gray-400 ml-1">({{ number_format($cuarto->valor_crudo, 0) }})</span>
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
                            {{ number_format($totalReg, 2) }}
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

</div>