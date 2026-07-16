<div>
    <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-gray-700">Personal del área</label>
        <button type="button"
                wire:click="$set('showModal', true)"
                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md transition-colors">
            + Agregar usuario
        </button>
    </div>

    <div class="border border-gray-200 rounded-md divide-y divide-gray-200">
        @forelse ($this->miembros as $miembro)
            <div class="flex items-center justify-between px-4 py-2">
                <div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $miembro->nombreCompleto }}
                        @if ($area->responsable_id == $miembro->id)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Responsable
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $miembro->cargo?->nombre ?? 'Sin cargo' }}
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if ($area->responsable_id == $miembro->id)
                        <button type="button" wire:click="quitarResponsable"
                                class="text-xs text-gray-500 hover:text-gray-700">
                            Quitar responsable
                        </button>
                    @else
                        <button type="button" wire:click="marcarResponsable({{ $miembro->id }})"
                                class="text-xs text-blue-600 hover:text-blue-800">
                            Marcar responsable
                        </button>
                    @endif
                    <button type="button"
                            wire:click="quitar({{ $miembro->id }})"
                            wire:confirm="¿Quitar a {{ $miembro->nombreCompleto }} del área?"
                            class="text-xs text-red-600 hover:text-red-800">
                        Quitar
                    </button>
                </div>
            </div>
        @empty
            <div class="px-4 py-3 text-sm text-gray-500">
                Esta área no tiene personal asignado todavía.
            </div>
        @endforelse
    </div>

    <p class="mt-1 text-xs text-gray-500">
        El responsable se elige entre los miembros del área.
    </p>

    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">Agregar usuario al área</x-slot>
        <x-slot name="content">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Buscar por nombre, usuario o legajo..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm mb-3">

            <div class="max-h-72 overflow-y-auto border border-gray-200 rounded-md divide-y divide-gray-200">
                @forelse ($this->disponibles as $u)
                    <div class="flex items-center justify-between px-3 py-2">
                        <div>
                            <div class="text-sm text-gray-900">{{ $u->nombreCompleto }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $u->name }} · {{ $u->area?->nombre ?? 'Sin área' }}
                            </div>
                        </div>
                        <button type="button"
                                wire:click="agregar({{ $u->id }})"
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                            Agregar
                        </button>
                    </div>
                @empty
                    <div class="px-3 py-3 text-sm text-gray-500">No hay usuarios para mostrar.</div>
                @endforelse
            </div>

            <p class="mt-2 text-xs text-gray-500">
                Se muestran hasta 25 resultados. Agregar un usuario lo mueve a esta área.
            </p>
        </x-slot>
        <x-slot name="footer">
            <button type="button"
                    wire:click="$set('showModal', false)"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Cerrar
            </button>
        </x-slot>
    </x-dialog-modal>
</div>
