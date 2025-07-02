<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <button wire:click="$set('open', true)" class="block w-full text-sm link-azul">
        Editar
    </button>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">Editar Periférico</x-slot>
        <x-slot name="content">
            <form action="{{route('inventario.perifericos.update', $periferico)}}" method="POST">
                @csrf
                @method('put')
                <div class="grid-datos-show">
                    <div class="atributo-edit">
                        Nombre
                    </div>
                    <div class="valor-edit">
                        <input type="text" name="nombre" value="{{$periferico->nombre}}" class="input-full">
                    </div>
                    <div class="atributo-edit">
                        Stock
                    </div>
                    <div class="valor-edit">
                        <input type="number" name="stock" value="{{$periferico->stock}}" class="input-full">
                    </div>
                    <div class="atributo-edit">
                    </div>
                    <div class="valor-edit text-right">
                        <button type="submit" class="boton-celeste">Guardar Edición</button>
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
