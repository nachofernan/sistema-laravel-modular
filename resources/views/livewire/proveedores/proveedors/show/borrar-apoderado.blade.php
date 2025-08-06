<div>
    <button class="inline-flex items-center px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors" wire:click="$set('open', true)"> 
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        Eliminar
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Eliminar {{ $apoderado->tipo == 'representante' ? 'Representante Legal' : 'Apoderado' }}
            </div>
        </x-slot> 
        <x-slot name="content"> 
            <form action="{{route('proveedores.apoderados.destroy', $apoderado)}}" method="POST">
                @csrf
                @method('delete')
                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <span class="text-red-800 font-medium">Atención!</span>
                    </div>
                    <p class="text-red-700 text-sm mt-1">Esta acción no puede deshacerse. Se eliminará {{ $apoderado->tipo == 'representante' ? 'el representante legal' : 'el apoderado' }} y todos sus documentos asociados.</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                        Eliminar
                    </button>
                </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div> 