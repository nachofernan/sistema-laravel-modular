<div>
    <button wire:click="$set('open', true)" 
            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Editar
    </button>

    <x-dialog-modal wire:model="open" maxWidth="md">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="text-base font-medium text-gray-900">Editar Periférico</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="space-y-4">
                <div>
                    <label for="nombre_edit" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Periférico *
                    </label>
                    <input type="text" 
                           id="nombre_edit"
                           wire:model="nombre"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Nombre del periférico">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock_edit" class="block text-sm font-medium text-gray-700 mb-2">
                        Stock Actual *
                    </label>
                    <input type="number" 
                           id="stock_edit"
                           wire:model="stock"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-yellow-50 rounded-md p-3">
                    <p class="text-xs text-yellow-700">
                        <strong>Atención:</strong> Al modificar el stock, se actualizará inmediatamente la disponibilidad del periférico en el sistema.
                    </p>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between items-center w-full">
                <button wire:click="$set('open', false)" 
                        type="button"
                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button wire:click="update" 
                        type="button"
                        class="px-3 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md shadow-sm hover:bg-yellow-700 transition-colors">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>