<div class="p-4">

    {{-- ── Encabezado ─────────────────────────────────────────── --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800">Máquinas</h2>
        <button wire:click="nuevaMaquina"
                class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
            + Nueva
        </button>
    </div>

    {{-- ── Formulario ──────────────────────────────────────────── --}}
    @if($showMaquinaForm)
    <div class="mb-4 p-4 border border-blue-300 bg-blue-50 rounded">
        <h3 class="font-semibold text-gray-700 mb-3 text-sm">
            {{ $editingMaquinaId ? 'Editar máquina' : 'Nueva máquina' }}
        </h3>
        <div class="grid grid-cols-2 gap-3">

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Código *</label>
                <input wire:model="maquina_codigo" type="text" maxlength="20"
                       class="w-full border rounded px-2 py-1 text-sm uppercase
                              @error('maquina_codigo') border-red-500 @enderror">
                @error('maquina_codigo')
                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre *</label>
                <input wire:model="maquina_nombre" type="text" maxlength="100"
                       class="w-full border rounded px-2 py-1 text-sm
                              @error('maquina_nombre') border-red-500 @enderror">
                @error('maquina_nombre')
                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                <textarea wire:model="maquina_descripcion" rows="2"
                          class="w-full border rounded px-2 py-1 text-sm"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input wire:model="maquina_activa" type="checkbox" id="maquina_activa" class="rounded">
                <label for="maquina_activa" class="text-sm text-gray-600">Activa</label>
            </div>

        </div>
        <div class="flex gap-2 mt-3">
            <button wire:click="guardarMaquina"
                    class="px-3 py-1.5 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                Guardar
            </button>
            <button wire:click="cancelarMaquina"
                    class="px-3 py-1.5 bg-gray-400 text-white text-sm rounded hover:bg-gray-500">
                Cancelar
            </button>
        </div>
    </div>
    @endif

    {{-- ── Tabla ───────────────────────────────────────────────── --}}
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left text-xs text-gray-600 uppercase">
                <th class="px-3 py-2 border">Código</th>
                <th class="px-3 py-2 border">Nombre</th>
                <th class="px-3 py-2 border text-center">Regs.</th>
                <th class="px-3 py-2 border text-center">Estado</th>
                <th class="px-3 py-2 border text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($maquinas as $maquina)

                <tr class="hover:bg-gray-50 {{ $expandedMaquinaId === $maquina->id ? 'bg-yellow-50' : '' }}">
                    <td class="px-3 py-2 border font-mono font-semibold">{{ $maquina->codigo }}</td>
                    <td class="px-3 py-2 border">{{ $maquina->nombre }}</td>
                    <td class="px-3 py-2 border text-center text-gray-500">{{ $maquina->registradores_count }}</td>
                    <td class="px-3 py-2 border text-center">
                        <button wire:click="toggleMaquina({{ $maquina->id }})"
                                class="text-xs px-2 py-0.5 rounded
                                       {{ $maquina->activa
                                          ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                          : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                            {{ $maquina->activa ? 'Activa' : 'Inactiva' }}
                        </button>
                    </td>
                    <td class="px-3 py-2 border text-center whitespace-nowrap">
                        <button wire:click="expandirMaquina({{ $maquina->id }})"
                                class="text-xs px-2 py-0.5 rounded bg-gray-200 hover:bg-gray-300 mr-1">
                            {{ $expandedMaquinaId === $maquina->id ? '▲' : '▼' }} Registradores
                        </button>
                        <button wire:click="editarMaquina({{ $maquina->id }})"
                                class="text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                            Editar
                        </button>
                    </td>
                </tr>

                {{-- Panel expandido --}}
                @if($expandedMaquinaId === $maquina->id)
                <tr>
                    <td colspan="5" class="border bg-gray-50 px-4 py-3">

                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Registradores asignados a {{ $maquina->codigo }}
                        </p>

                        @if($registradoresAsignados->isEmpty())
                            <p class="text-xs text-gray-400 italic mb-3">Sin registradores asignados.</p>
                        @else
                        <table class="w-full text-xs border-collapse mb-3">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-left">
                                    <th class="px-2 py-1 border">Código</th>
                                    <th class="px-2 py-1 border text-center">Tipo</th>
                                    <th class="px-2 py-1 border text-center">Factor</th>
                                    <th class="px-2 py-1 border text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registradoresAsignados as $reg)
                                <tr class="hover:bg-gray-100">
                                    <td class="px-2 py-1 border font-mono font-semibold">{{ $reg->codigo }}</td>
                                    <td class="px-2 py-1 border text-center">
                                        <span class="px-1.5 py-0.5 rounded text-xs
                                            @if($reg->tipo === 'principal')  bg-blue-100 text-blue-700
                                            @elseif($reg->tipo === 'respaldo') bg-green-100 text-green-700
                                            @elseif($reg->tipo === 'control')  bg-purple-100 text-purple-700
                                            @else bg-gray-100 text-gray-500
                                            @endif">
                                            {{ ucfirst($reg->tipo) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 border text-center font-mono">{{ $reg->factor_conversion }}</td>
                                    <td class="px-2 py-1 border text-center">
                                        <button wire:click="desasignarRegistrador({{ $maquina->id }}, {{ $reg->id }})"
                                                wire:confirm="¿Desasignar {{ $reg->codigo }} de {{ $maquina->codigo }}?"
                                                class="text-xs px-2 py-0.5 rounded bg-red-100 text-red-600 hover:bg-red-200">
                                            Quitar
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                        {{-- Agregar registrador --}}
                        @if($registradoresDisponibles->isNotEmpty())
                        <div class="flex items-end gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Agregar registrador</label>
                                <select wire:model="asignar_registrador_id"
                                        class="border rounded px-2 py-1 text-xs min-w-48">
                                    <option value="">— Seleccioná —</option>
                                    @foreach($registradoresDisponibles as $reg)
                                        <option value="{{ $reg->id }}">
                                            {{ $reg->codigo }}{{ $reg->nombre ? ' — '.$reg->nombre : '' }}
                                            ({{ ucfirst($reg->tipo) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('asignar_registrador_id')
                                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>
                            <button wire:click="asignarRegistrador({{ $maquina->id }})"
                                    class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 mb-0.5">
                                Asignar
                            </button>
                        </div>
                        @else
                            <p class="text-xs text-gray-400 italic">Todos los registradores activos ya están asignados.</p>
                        @endif

                    </td>
                </tr>
                @endif

            @empty
                <tr>
                    <td colspan="5" class="px-3 py-4 text-center text-gray-400 text-sm italic border">
                        No hay máquinas cargadas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>