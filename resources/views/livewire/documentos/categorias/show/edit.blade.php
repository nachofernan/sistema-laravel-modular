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
                <svg class="h-5 w-5 text-blue-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar categoría
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label for="nombre_edit" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre
                    </label>
                    <input type="text"
                           id="nombre_edit"
                           wire:model="nombre"
                           placeholder="Nombre de la categoría"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="orden_edit" class="block text-sm font-medium text-gray-700 mb-1">
                        Orden
                    </label>
                    <input type="number"
                           id="orden_edit"
                           min="0"
                           wire:model="orden"
                           class="w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('orden')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Posición en el menú. Menor número, más arriba.
                    </p>
                </div>

                <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="checkbox"
                           wire:model="publica"
                           class="mt-0.5 h-4 w-4 shrink-0 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">
                        Visible sin iniciar sesión
                        <span class="block mt-0.5 text-xs font-normal text-gray-500">
                            Si se desmarca, la categoría y sus documentos dejan de aparecer en el portal público.
                        </span>
                    </span>
                </label>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between items-center w-full">
                <button wire:click="$set('open', false)"
                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button wire:click="actualizar"
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
