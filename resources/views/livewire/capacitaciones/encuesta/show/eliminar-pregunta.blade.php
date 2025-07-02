<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="w-full text-right">
        <button wire:click="$set('open', true)" class="text-xs link-azul text-red-600">
            Eliminar
        </button>
    </div>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="text-left border-b pb-2">
                Eliminar Pregunta
            </div>
        </x-slot>
        <x-slot name="content">
            <form action="{{ route('capacitaciones.preguntas.update', $pregunta) }}" method="POST">
                @csrf
                @method('delete')
                <div class="grid grid-cols-10 gap-6">
                    <div class="col-span-10">
                        ¿Borrar la pregunta?
                    </div>
                    <div class="col-span-10 text-right">
                        <button type="submit" class="boton-celeste bg-red-300 hover:bg-red-400">Borrar Pregunta</button>
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
