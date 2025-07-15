<div>
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition-colors">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Nueva
    </button>

    <x-dialog-modal wire:model="open" maxWidth="md">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="text-base font-medium text-gray-900">Nueva Característica</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label for="nombre_caracteristica" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Característica *
                    </label>
                    <input type="text" 
                           id="nombre_caracteristica"
                           wire:model="nombre"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ej: Memoria RAM, Procesador, etc.">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               wire:model="opciones"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">¿Esta característica tendrá opciones predefinidas?</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">
                        Si marca esta opción, podrá definir valores específicos para esta característica. 
                        Si no la marca, será un campo de texto libre.
                    </p>
                </div>

                <div class="bg-blue-50 rounded-md p-3">
                    <p class="text-xs text-blue-700">
                        <strong>Nota:</strong> Las características permiten definir propiedades específicas para todos los elementos de esta categoría ({{ $categoria->nombre }}).
                    </p>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between items-center w-full">
                <button wire:click="$set('open', false)" 
                        type="button"
                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button wire:click="guardar" 
                        type="button"
                        class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition-colors">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Crear Característica
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>