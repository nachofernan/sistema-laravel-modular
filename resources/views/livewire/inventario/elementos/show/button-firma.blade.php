<div class="float-right">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <span class="bg-yellow-400"></span>
    <span class="bg-green-400"></span>
    <span class="bg-blue-400"></span>
    <button wire:click="$set('open', true)" class="rounded py-1 px-3 text-xs bg-{{ $color }}-400">
        {{ $texto_boton }}
    </button>
    @can('Inventario/Elementos/Editar')
        <x-dialog-modal wire:model="open">
            <x-slot name="title">Validar Entrega</x-slot>
            <x-slot name="content">
                {{ $texto_descripcion }}
            </x-slot>
            <x-slot name="footer">
                <div class="text-center">
                    @if (!$elemento->entregaActual())
                        <button wire:click="activar(1)" class="px-6 py-3 bg-blue-400 rounded font-bold">Solicitar
                            Firma</button>
                    @else
                        @if ($this->elemento->entregaActual()->fecha_firma)
                            <button wire:click="activar(2)"
                                class="px-6 py-3 bg-green-400 rounded font-bold">Devolución</button>
                        @else
                            <button wire:click="activar(2)" class="px-6 py-3 bg-lime-400 rounded font-bold">Marcar como
                                Entregado</button>
                            <button wire:click="activar(3)" class="px-6 py-3 bg-yellow-400 rounded font-bold">Cancelar Firma
                                y Entrega</button>
                        @endif
                    @endif
                </div>
            </x-slot>
        </x-dialog-modal>
    @endcan
</div>
