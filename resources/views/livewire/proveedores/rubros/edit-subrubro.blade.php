<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="text-xs">
        <button class="link-azul" wire:click="$set('open', true)"> 
            Editar
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="grid grid-cols-12 gap-6 border-b py-2"> 
                <div class="col-span-3 font-bold">
                    Editar Subrubro
                </div>
            </div>                    
        </x-slot> 
        <x-slot name="content">
            <div class="grid grid-cols-12 gap-6"> 
                <div class="col-span-7 font-bold">
                    <input type="text" class="input-full" wire:model="nombre">
                </div>
                <div class="col-span-1"></div>
                <div class="col-span-4 text-right">
                    <button wire:click="actualizar" class="w-full boton-celeste">Actualizar</button>
                </div>
            </div>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
        </div>
    </x-dialog-modal> 
</div>
