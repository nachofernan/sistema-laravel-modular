<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <button wire:click="$set('open', true)" class="block w-full text-sm boton-celeste">
        Nuevo Periférico
    </button>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">Nuevo Periférico</x-slot>
        <x-slot name="content">
            <form action="{{route('inventario.perifericos.store')}}" method="POST">
                @csrf
                <div class="grid-datos-show">
                    <div class="atributo-edit">
                        Nombre
                    </div>
                    <div class="valor-edit">
                        <input type="text" name="nombre" class="input-full">
                    </div>
                    <div class="atributo-edit">
                        Stock
                    </div>
                    <div class="valor-edit">
                        <input type="number" name="stock" class="input-full">
                    </div>
                    <div class="atributo-edit">
                    </div>
                    <div class="valor-edit text-right">
                        <button type="submit" class="boton-celeste">Crear Nuevo</button>
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
