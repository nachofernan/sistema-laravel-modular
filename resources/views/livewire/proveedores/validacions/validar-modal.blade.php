<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <button class="hover:underline text-blue-600" wire:click="$set('open', true)"> 
        Acciones
    </button> 
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <span class="text-lg font-semibold">Validar o Rechazar Documento</span>
        </x-slot>
    
        <x-slot name="content">
            <div class="space-y-4">
                <div class="text-sm text-gray-600">
                    Por favor, revisa el documento y selecciona una de las siguientes opciones:
                </div>
                @if ($validacion->documento->requiereVencimiento())
                <div class="space-y-2">
                    <div class="flex items-center">
                        <label for="validado" class="ml-2 block text-sm text-gray-900">
                            Vencimiento
                        </label>
                        <input type="date" wire:model="vencimiento" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500 ml-2">
                    </div>
                </div>
                @endif

                <div class="space-y-2">
                    <button class="w-full bg-green-600 text-white py-2 text-lg rounded hover:bg-green-700 transition duration-200" wire:click="validar">
                        Validar Documento
                    </button>
                </div>
    
                <hr class="my-4">
    
                <div class="space-y-2">
                    <div class="text-sm text-gray-600">
                        Si decides rechazar el documento, por favor, indica el motivo:
                    </div>
                    <textarea rows="4" wire:model="comentarios" class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @error('comentarios')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
    
                <div class="space-y-2">
                    <button class="w-full bg-red-600 text-white py-2 text-lg rounded hover:bg-red-700 transition duration-200" wire:click="rechazar">
                        Rechazar Documento
                    </button>
                </div>
    
                <div class="text-sm text-gray-500 mt-4">
                    <strong>Nota:</strong> En caso de rechazar el documento, este será eliminado y el motivo de rechazo se enviará por correo electrónico.
                </div>
            </div>
        </x-slot>
    
        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <button class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800" wire:click="$set('open', false)">
                    Cancelar
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
