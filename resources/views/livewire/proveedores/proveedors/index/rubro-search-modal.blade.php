<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <button type="button" wire:click="$set('open', true)"
        class="px-3 py-1 text-blue-500 hover:underline w-full text-sm"
        title="Buscar rubro/subrubro">
        Buscar Rubro/Subrubro
    </button>

    <!-- Modal -->
    <x-dialog-modal wire:model="open" maxWidth="7xl">
        <x-slot name="title">
            <div class="border-b py-2 grid grid-cols-2">
                <div class="font-bold col">
                    Seleccionar Rubro/Subrubro
                </div>
                <div class="col">
                    <input type="text" wire:model.live="search" class="input-full"
                        placeholder="Buscar rubro o subrubro...">
                </div>
            </div>
        </x-slot>

        <x-slot name="content">
            @if (count($resultados) > 0)
                @foreach ($resultados as $resultado)
                    <div class="grid grid-cols-12 gap-6 border-b py-2">
                        <div class="col-span-3 font-bold">
                            {{ $resultado['rubro']->nombre }}
                            <div class="mt-2">
                                <button type="button" wire:click="selectRubro({{ $resultado['rubro']->id }})"
                                    class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Seleccionar Rubro
                                </button>
                            </div>
                        </div>
                        <div class="col-span-9">
                            @foreach ($resultado['subrubros'] as $subrubro)
                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                    <span>{{ $subrubro->nombre }}</span>
                                    <button type="button"
                                        wire:click="selectSubrubro({{ $resultado['rubro']->id }}, {{ $subrubro->id }})"
                                        class="text-xs px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                        Seleccionar
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                @if (strlen($search) > 2)
                    <div class="text-center text-gray-500 py-8">
                        No se encontraron resultados para "{{ $search }}"
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        Escribe al menos 3 caracteres para buscar
                    </div>
                @endif
            @endif
        </x-slot>

        <x-slot name="footer">
            <button type="button" wire:click="$set('open', false)"
                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Cancelar
            </button>
        </x-slot>
    </x-dialog-modal>
</div>
