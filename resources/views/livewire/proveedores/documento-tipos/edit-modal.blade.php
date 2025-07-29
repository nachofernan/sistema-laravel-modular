<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <button wire:click="$set('open', true)" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
        Editar
    </button>
    
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="text-lg font-medium text-gray-900 text-left">
                Editar Tipo de Documento
            </div>
        </x-slot>
        
        <x-slot name="content">
            <div class="text-left">
                <form wire:submit.prevent="update">
                    <div class="space-y-4">
                        
                        <!-- Código -->
                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                                Código <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="codigo"
                                   wire:model="codigo" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('codigo') border-red-300 @enderror" 
                                   placeholder="Código del documento">
                            @error('codigo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nombre"
                                   wire:model="nombre" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('nombre') border-red-300 @enderror" 
                                   placeholder="Nombre del documento">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vencimiento -->
                        <div>
                            <label for="vencimiento" class="block text-sm font-medium text-gray-700 mb-2">
                                ¿Tiene vencimiento?
                            </label>
                            <select wire:model="vencimiento" 
                                    id="vencimiento"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </x-slot>
        
        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        wire:click="$set('open', false)"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="button" 
                        wire:click="update"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <span wire:loading.remove>Guardar</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Guardando...
                    </span>
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
