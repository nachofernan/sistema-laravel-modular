<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="text-right">
        <button class="link-azul text-sm" type="submit" wire:click="$set('open', true)"> 
            Nuevo Contacto
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            Cargar Nuevo Contacto
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.contactos.store')}}" method="post">
            @csrf
            <div class="grid grid-cols-10 gap-4">
                <input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
                <div class="col-span-2 text-right mt-2">
                    Nombre
                </div>
                <div class="col-span-8">
                    <input type="text" name="nombre" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Teléfono
                </div>
                <div class="col-span-8">
                    <input type="text" name="telefono" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Correo
                </div>
                <div class="col-span-8">
                    <input type="text" name="correo" class="input-full">
                </div>
            </div>
            <div class="text-right pt-4">
                <button type="submit" class="boton-celeste">Guardar Contacto</button>
            </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
