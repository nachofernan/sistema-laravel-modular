<div>
    {{-- Success is as dangerous as failure. --}}
    <div class="text-right">
        <button class="link-azul text-sm" type="submit" wire:click="$set('open', true)"> 
            Nuevo Documento
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            Cargar nuevo documento
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.documentos.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-10 gap-4">
                <input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
                
                <div class="col-span-3 text-right mt-2">
                    Archivo
                </div>
                <div class="col-span-7">
                    <input type="file" name="file" class="input-full" required>
                </div>
                
                <div class="col-span-3 text-right mt-2">
                    Tipo de Documento
                </div>
                <div class="col-span-7">
                    <select name="documento_tipo_id" wire:model.live="documento_tipo_id" class="input-full" required>
                        <option value="">Seleccionar tipo de documento</option>
                        @foreach ($documentoTipos as $documentoTipo)
                            <option value="{{$documentoTipo->id}}">{{$documentoTipo->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                
                @if($requiere_vencimiento)
                    <div class="col-span-3 text-right mt-2">
                        Vencimiento
                    </div>
                    <div class="col-span-7">
                        <input type="date" name="vencimiento" class="input-full">
                    </div>
                @endif
            </div>
            <div class="text-right pt-4">
                <button type="submit" class="boton-celeste">Cargar Documento</button>
            </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>