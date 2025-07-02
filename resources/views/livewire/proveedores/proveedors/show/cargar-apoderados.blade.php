<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <div class="text-right">
        <button class="link-azul text-sm" type="submit" wire:click="$set('open', true)"> 
            Nuevo Apoderado
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            Cargar Nuevo Apoderado
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.apoderados.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-10 gap-4">
                <input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
                <div class="col-span-2 text-right mt-2">
                    Tipo
                </div>
                <div class="col-span-8">
                    <select name="tipo" wire:model.live="tipo" class="input-full">
                        <option value="apoderado">Apoderado</option>
                        <option value="representante">Representante Legal</option>
                    </select>
                </div>
                <div class="col-span-2 text-right mt-2">
                    Archivo
                </div>
                <div class="col-span-8">
                    <input type="file" name="file" class="input-full" required>
                </div>
                @if ($tipo == 'representante')
                    <div class="col-span-2 text-right mt-2">
                    Nombre
                </div>
                <div class="col-span-8">
                    <input type="text" name="nombre" class="input-full" value="{{$proveedor->razonsocial}}" required>
                </div>
                <div class="col-span-2 text-right mt-2">
                    Vencimiento
                </div>
                <div class="col-span-8">
                    <input type="date" name="vencimiento" class="input-full" value="{{now()->addYear()->format('Y-m-d')}}">
                </div>
                @endif
                
            </div>
            <div class="text-right pt-4">
                <button type="submit" class="boton-celeste">Guardar Apoderado</button>
            </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
