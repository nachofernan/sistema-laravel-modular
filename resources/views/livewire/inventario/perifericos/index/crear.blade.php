<div>
    <button wire:click="$set('open', true)" 
            class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Nuevo Periférico
    </button>

    <x-dialog-modal wire:model="open" maxWidth="md">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="text-base font-medium text-gray-900">Crear Nuevo Periférico</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="store" class="space-y-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Periférico *
                    </label>
                    <input type="text" 
                           id="nombre"
                           wire:model="nombre"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ej: Mouse inalámbrico, Teclado USB, etc.">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                        Stock Inicial *
                    </label>
                    <input type="number" 
                           id="stock"
                           wire:model="stock"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="0">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 rounded-md p-3">
                    <p class="text-xs text-blue-700">
                        <strong>Nota:</strong> Los periféricos son elementos auxiliares que se pueden asignar junto con elementos principales del inventario.
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
                <button wire:click="store" 
                        type="button"
                        class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition-colors">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Crear Periférico
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>