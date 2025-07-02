<div>
    {{-- Success is as dangerous as failure. --}}
    <div class="text-xs">
        <button class="link-azul" wire:click="$set('open', true)"> 
            Nuevo
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="grid grid-cols-12 gap-6 border-b py-2"> 
                <div class="col-span-3 font-bold">
                    Crear Subrubro
                </div>
            </div>                    
        </x-slot> 
        <x-slot name="content">
            <div class="grid grid-cols-12 gap-6"> 
                <div class="col-span-7 font-bold">
                    <input type="text" wire:model="nombre" class="input-full" placeholder="Nombre del Subrubro">
                </div>
                <div class="col-span-1"></div>
                <div class="col-span-4 text-right">
                    <button wire:click="crear" class="w-full boton-celeste">Crear</button>
                </div>
            </div>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
        </div>
    </x-dialog-modal> 
</div>
