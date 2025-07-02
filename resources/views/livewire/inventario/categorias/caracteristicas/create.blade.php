<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <button wire:click="$set('open', true)" class="boton-celeste w-full text-sm py-1 px-0">Nueva</button>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Nueva característica
        </x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-10 gap-6 px-5 py-8">
                <div class="col-span-3 text-right py-2">
                    Nombre
                </div>
                <div class="col-span-7 font-bold">
                    <input type="text" wire:model="nombre" class="input-full border-b" placeholder="Nombre">
                </div>
                <div class="col-span-3 text-right">
                    ¿Va a tener opciones?
                </div>
                <div class="col-span-7 font-bold">
                    <input type="checkbox" wire:model="opciones" class="rounded border-b py-2">
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <button wire:click="guardar()" class="boton-celeste">Guardar</button>
        </x-slot>
    </x-dialog-modal>
</div>
