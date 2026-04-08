<div class="p-4">

    {{-- ── Encabezado ─────────────────────────────────────────── --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800">Registradores</h2>
        <button wire:click="nuevo"
                class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
            + Nuevo
        </button>
    </div>

    {{-- ── Formulario ──────────────────────────────────────────── --}}
    @if($showForm)
    <div class="mb-4 p-4 border border-blue-300 bg-blue-50 rounded">
        <h3 class="font-semibold text-gray-700 mb-3 text-sm">
            {{ $editingId ? 'Editar registrador' : 'Nuevo registrador' }}
        </h3>
        <div class="grid grid-cols-2 gap-3">

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Código *</label>
                <input wire:model="reg_codigo" type="text" maxlength="30"
                       class="w-full border rounded px-2 py-1 text-sm uppercase
                              @error('reg_codigo') border-red-500 @enderror">
                @error('reg_codigo')
                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
                <input wire:model="reg_nombre" type="text" maxlength="100"
                       class="w-full border rounded px-2 py-1 text-sm">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tipo de registrador *</label>
                <select wire:model="reg_tipo" class="w-full border rounded px-2 py-1 text-sm">
                    <option value="principal">Principal</option>
                    <option value="respaldo">Respaldo</option>
                    <option value="control">Control</option>
                    <option value="auxiliar">Auxiliar</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tipo de dato *</label>
                <select wire:model="reg_tipo_dato" class="w-full border rounded px-2 py-1 text-sm">
                    <option value="pulsos">Pulsos</option>
                    <option value="potencia">Potencia</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Columna de datos *</label>
                <input wire:model="reg_columna_datos" type="number" min="1" max="20"
                       class="w-full border rounded px-2 py-1 text-sm
                              @error('reg_columna_datos') border-red-500 @enderror">
                @error('reg_columna_datos')
                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Factor de conversión *</label>
                <input wire:model="reg_factor" type="number" step="0.000001" min="0.000001"
                       class="w-full border rounded px-2 py-1 text-sm
                              @error('reg_factor') border-red-500 @enderror">
                @error('reg_factor')
                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Solo al crear: asignación inicial opcional --}}
            @if(!$editingId)
            <div class="col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    Asignar a máquina <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <select wire:model="reg_maquina_id" class="w-full border rounded px-2 py-1 text-sm">
                    <option value="">— Sin asignar por ahora —</option>
                    @foreach($todasLasMaquinas as $maquina)
                        <option value="{{ $maquina->id }}">{{ $maquina->codigo }} — {{ $maquina->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Notas</label>
                <textarea wire:model="reg_notas" rows="2"
                          class="w-full border rounded px-2 py-1 text-sm"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input wire:model="reg_activo" type="checkbox" id="reg_activo" class="rounded">
                <label for="reg_activo" class="text-sm text-gray-600">Activo</label>
            </div>

        </div>
        <div class="flex gap-2 mt-3">
            <button wire:click="guardar"
                    class="px-3 py-1.5 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                Guardar
            </button>
            <button wire:click="cancelar"
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
                <th class="px-3 py-2 border text-center">Tipo</th>
                <th class="px-3 py-2 border text-center">Col.</th>
                <th class="px-3 py-2 border text-center">Factor</th>
                <th class="px-3 py-2 border text-center">Máqs.</th>
                <th class="px-3 py-2 border text-center">Estado</th>
                <th class="px-3 py-2 border text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registradores as $reg)

                <tr class="hover:bg-gray-50 {{ $expandedRegistradorId === $reg->id ? 'bg-yellow-50' : '' }}">
                    <td class="px-3 py-2 border font-mono font-semibold">{{ $reg->codigo }}</td>
                    <td class="px-3 py-2 border text-center">
                        <span class="px-1.5 py-0.5 rounded text-xs
                            @if($reg->tipo === 'principal')  bg-blue-100 text-blue-700
                            @elseif($reg->tipo === 'respaldo') bg-green-100 text-green-700
                            @elseif($reg->tipo === 'control')  bg-purple-100 text-purple-700
                            @else bg-gray-100 text-gray-500
                            @endif">
                            {{ ucfirst($reg->tipo) }}
                        </span>
                    </td>
                    <td class="px-3 py-2 border text-center font-mono">{{ $reg->columna_datos }}</td>
                    <td class="px-3 py-2 border text-center font-mono">{{ $reg->factor_conversion }}</td>
                    <td class="px-3 py-2 border text-center text-gray-500">{{ $reg->maquinas_count }}</td>
                    <td class="px-3 py-2 border text-center">
                        <button wire:click="toggle({{ $reg->id }})"
                                class="text-xs px-2 py-0.5 rounded
                                       {{ $reg->activo
                                          ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                          : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                            {{ $reg->activo ? 'Activo' : 'Inactivo' }}
                        </button>
                    </td>
                    <td class="px-3 py-2 border text-center whitespace-nowrap">
                        <button wire:click="expandir({{ $reg->id }})"
                                class="text-xs px-2 py-0.5 rounded bg-gray-200 hover:bg-gray-300 mr-1">
                            {{ $expandedRegistradorId === $reg->id ? '▲' : '▼' }} Máquinas
                        </button>
                        <button wire:click="editar({{ $reg->id }})"
                                class="text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                            Editar
                        </button>
                    </td>
                </tr>

                {{-- Panel expandido: máquinas asignadas --}}
                @if($expandedRegistradorId === $reg->id)
                <tr>
                    <td colspan="9" class="border bg-gray-50 px-4 py-3">

                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                            Máquinas que usan {{ $reg->codigo }}
                        </p>

                        @if($maquinasAsignadas->isEmpty())
                            <p class="text-xs text-gray-400 italic mb-3">No está asignado a ninguna máquina.</p>
                        @else
                        <table class="w-full text-xs border-collapse mb-3">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-left">
                                    <th class="px-2 py-1 border">Código</th>
                                    <th class="px-2 py-1 border">Nombre</th>
                                    <th class="px-2 py-1 border text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maquinasAsignadas as $maquina)
                                <tr class="hover:bg-gray-100">
                                    <td class="px-2 py-1 border font-mono font-semibold">{{ $maquina->codigo }}</td>
                                    <td class="px-2 py-1 border">{{ $maquina->nombre }}</td>
                                    <td class="px-2 py-1 border text-center">
                                        <button wire:click="desasignarMaquina({{ $reg->id }}, {{ $maquina->id }})"
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

                        {{-- Asignar a otra máquina --}}
                        @if($maquinasDisponibles->isNotEmpty())
                        <div class="flex items-end gap-2">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Asignar a máquina</label>
                                <select wire:model="asignar_maquina_id"
                                        class="border rounded px-2 py-1 text-xs min-w-48">
                                    <option value="">— Seleccioná —</option>
                                    @foreach($maquinasDisponibles as $maquina)
                                        <option value="{{ $maquina->id }}">{{ $maquina->codigo }} — {{ $maquina->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('asignar_maquina_id')
                                    <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>
                            <button wire:click="asignarMaquina({{ $reg->id }})"
                                    class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 mb-0.5">
                                Asignar
                            </button>
                        </div>
                        @else
                            <p class="text-xs text-gray-400 italic">Ya está asignado a todas las máquinas activas.</p>
                        @endif

                    </td>
                </tr>
                @endif

            @empty
                <tr>
                    <td colspan="9" class="px-3 py-4 text-center text-gray-400 text-sm italic border">
                        No hay registradores cargados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>