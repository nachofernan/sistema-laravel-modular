<div>
    {{-- Stop trying to control. --}}
    <button class="hover:underline text-blue-600" wire:click="$set('open', true)"> 
        Editar
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            <small>Editar Vencimiento del Documento</small> 
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.documentos.update', $documento)}}" method="post">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-6"> 
                <div class="col-span-7 font-bold">
                    <input type="date" name="vencimiento" class="input-full" value="{{$documento->vencimiento ? $documento->vencimiento->format('Y-m-d') : ''}}">
                </div>
                <div class="col-span-1"></div>
                <div class="col-span-4 text-right">
                    <button type="submit" class="w-full boton-celeste">Actualizar</button>
                </div>
            </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
