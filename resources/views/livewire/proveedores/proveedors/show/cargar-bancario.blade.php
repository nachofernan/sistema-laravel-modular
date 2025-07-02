<div>
    {{-- Be like water. --}}
    <div class="text-right">
        <button class="link-azul text-sm" type="submit" wire:click="$set('open', true)"> 
            Nuevo Registro Bancario
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            Cargar Nuevo Registro Bancario
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.bancarios.store')}}" method="post">
            @csrf
            <div class="grid grid-cols-10 gap-4">
                <input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
                <div class="col-span-2 text-right mt-2">
                    Tipo de Cuenta
                </div>
                <div class="col-span-8">
                    <select name="tipocuenta" class="input-full">
                        <option value="Caja de Ahorro">Caja de Ahorro</option>
                        <option value="Cuenta Corriente">Cuenta Corriente</option>
                    </select>
                </div>
                <div class="col-span-2 text-right mt-2">
                    Número de Cuenta
                </div>
                <div class="col-span-8">
                    <input type="text" name="numerocuenta" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    CBU/CVU
                </div>
                <div class="col-span-8">
                    <input type="text" name="cbu" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Alias
                </div>
                <div class="col-span-8">
                    <input type="text" name="alias" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Banco
                </div>
                <div class="col-span-8">
                    <input type="text" name="banco" class="input-full">
                </div>
                <div class="col-span-2 text-right mt-2">
                    Sucursal
                </div>
                <div class="col-span-8">
                    <input type="text" name="sucursal" class="input-full">
                </div>
            </div>
            <div class="text-right pt-4">
                <button type="submit" class="boton-celeste">Cargar Registro Bancario</button>
            </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
