<div>
    {{-- Success is as dangerous as failure. --}}
    <div class="text-right">
        <button class="link-azul text-sm" type="submit" wire:click="$set('open', true)"> 
            Nueva Dirección
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            Cargar Nueva Dirección
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.direccions.store')}}" method="post">
            @csrf
            <div class="grid grid-cols-10 gap-4">
                <input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
                <div class="col-span-2 text-right mt-2">
                    Tipo
                </div>
                <div class="col-span-8">
                    <input type="text" name="tipo" class="input-full" placeholder="Ingrese el tipo de dirección (almacenes, ventas, central, etc)">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Calle
                </div>
                <div class="col-span-8">
                    <input type="text" name="calle" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Altura
                </div>
                <div class="col-span-8">
                    <input type="text" name="altura" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Piso
                </div>
                <div class="col-span-8">
                    <input type="text" name="piso" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Departamento
                </div>
                <div class="col-span-8">
                    <input type="text" name="departamento" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Ciudad
                </div>
                <div class="col-span-8">
                    <input type="text" name="ciudad" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Código Postal
                </div>
                <div class="col-span-8">
                    <input type="text" name="codigopostal" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Provincia
                </div>
                <div class="col-span-8">
                    <input type="text" name="provincia" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    País
                </div>
                <div class="col-span-8">
                    <input type="text" name="pais" class="input-full">
                </div>
            </div>
            <div class="text-right pt-4">
                <button type="submit" class="boton-celeste">Guardar Dirección</button>
            </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
