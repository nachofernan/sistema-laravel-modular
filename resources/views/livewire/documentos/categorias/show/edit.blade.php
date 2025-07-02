<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <button wire:click="$set('open', true)" class="text-sm boton-celeste">
        Editar
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title">
            <div class="text-left border-b pb-2">
                Editar nombre de Categoría
            </div>
        </x-slot> 
        <x-slot name="content"> 
            <div class="grid grid-cols-10 gap-6">
                <div class="col-span-2 atributo-edit">
                    Nombre
                </div>
                <div class="col-span-8">
                    <input type="text" class="input-full" wire:model="nombre" value="{{$nombre}}">
                </div>
            </div>
        </x-slot> 
        <x-slot name="footer"> 
            <div class="text-center">
                <button wire:click="actualizar()" class="boton-celeste">Actualizar</button>
            </div>
        </x-slot> 
    </x-dialog-modal> 
</div>
