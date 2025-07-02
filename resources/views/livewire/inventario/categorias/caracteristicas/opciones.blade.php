<div>
    {{-- Do your work, then step back. --}}
    <button wire:click="$set('open', true)" class="link-azul py-1 px-0">Editar</button>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="text-left">
                Listado de Opciones de "{{$caracteristica->nombre}}" de "{{$caracteristica->categoria->nombre}}"
            </div>
        </x-slot>
        <x-slot name="content">
            <div class="w-8/12 p-3 mx-auto text-left">
            @if ($caracteristica->opciones->count())
                <ul>
                    @foreach ($caracteristica->opciones as $opcion)
                        <li>- {{ $opcion->nombre }}</li>
                    @endforeach
                </ul>
            @else
                <i>Sin opciones</i>
            @endif
            </div>
            
            
            <div class="w-10/12 shadow-xl border-2 mt-4 p-3 mx-auto">
                <div class="subtitulo-show text-sm">Nueva Opción</div>
                <div class="grid grid-cols-10 gap-6 px-5">
                    <div class="col-span-3 text-right py-2">
                        Nombre
                    </div>
                    <div class="col-span-4 font-bold">
                        <input type="text" wire:model="nombre" class="input-full border-b" placeholder="Nombre">
                    </div>
                    <div class="col-span-3">
                        <button wire:click="guardar()" class="boton-celeste w-full">Guardar</button>
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
