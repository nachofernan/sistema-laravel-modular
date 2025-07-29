<div>
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-md transition-colors">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Editar
    </button> 

    <x-dialog-modal wire:model="open" maxWidth="md"> 
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="text-base font-medium text-gray-900">Editar Categoría</span>
            </div>
        </x-slot> 

        <x-slot name="content"> 
            <div class="space-y-3">
                <div>
                    <label for="nombre_edit" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Categoría
                    </label>
                    <input type="text" 
                           id="nombre_edit"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                           wire:model="nombre" 
                           placeholder="Ingrese el nombre de la categoría">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 rounded-md p-3">
                    <p class="text-xs text-blue-700">
                        <strong>Nota:</strong> Al cambiar el nombre de la categoría se actualizará inmediatamente en todo el sistema.
                    </p>
                </div>
            </div>
        </x-slot> 

        <x-slot name="footer"> 
            <div class="flex justify-between items-center w-full">
                <button wire:click="$set('open', false)" 
                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button wire:click="actualizar()" 
                        class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition-colors">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar
                </button>
            </div>
        </x-slot> 
    </x-dialog-modal> 
</div>