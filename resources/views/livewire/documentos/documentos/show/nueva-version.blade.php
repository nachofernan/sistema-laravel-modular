<div>
    <button wire:click="$set('open', true)"
            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
        </svg>
        Nueva versión
    </button>

    <x-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <span class="text-base font-medium text-gray-900">Subir nueva versión</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-3">
                <div class="bg-gray-50 rounded-md p-3 text-xs text-gray-600">
                    <span class="font-medium text-gray-800">{{ $documento->nombre }}</span> —
                    versión actual <span class="font-medium">v{{ $documento->version }}</span>,
                    archivo <span class="font-mono">{{ $documento->archivo }}</span>.
                </div>

                <div>
                    <label for="archivo_version" class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo nuevo
                    </label>
                    <input type="file"
                           id="archivo_version"
                           wire:model="archivo"
                           class="w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                    <div wire:loading wire:target="archivo" class="mt-1 text-xs text-gray-500">
                        Subiendo archivo…
                    </div>

                    @error('archivo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="codigo_version" class="block text-sm font-medium text-gray-700 mb-2">
                        Código
                    </label>
                    <input type="text"
                           id="codigo_version"
                           wire:model="codigo"
                           placeholder="Sin código"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('codigo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        El código de Control de Gestión, si la nueva versión trae uno distinto.
                    </p>
                </div>

                <div>
                    <label for="notas_version" class="block text-sm font-medium text-gray-700 mb-2">
                        Qué cambió
                    </label>
                    <input type="text"
                           id="notas_version"
                           wire:model="notas"
                           placeholder="Ej: actualización anual, corrección del anexo II"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('notas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 rounded-md p-3">
                    <p class="text-xs text-blue-700">
                        <strong>Nota:</strong> el archivo actual no se borra: pasa al historial de versiones
                        y el documento queda en v{{ $documento->version + 1 }}. El resto de los datos se
                        cambian desde la edición.
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
                <button wire:click="guardar"
                        wire:loading.attr="disabled"
                        wire:target="archivo,guardar"
                        class="px-3 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Guardar versión
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
