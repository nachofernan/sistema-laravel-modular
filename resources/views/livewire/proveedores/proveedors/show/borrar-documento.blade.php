<div>
    {{-- Do your work, then step back. --}}
    <button class="link-azul" wire:click="$set('open', true)">  
        Eliminar 
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            Eliminar Documento
        </x-slot> 
        <x-slot name="content"> 
            <form action="{{route('proveedores.documentos.destroy', $documento)}}" method="POST">
                @csrf
                @method('delete')
                <div class="w-full p-2 my-4 rounded-lg border border-lg border-red-200"><span class="text-red-500 font-bold">Atención!</span> Esta acción no puede deshacerse</div>
                <div class="w-full text-right">
                    <button type="submit" class="boton-celeste bg-red-400 text-white hover:bg-red-500">Eliminar</button>
                </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
