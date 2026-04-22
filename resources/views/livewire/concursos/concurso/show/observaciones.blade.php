<div>
    <button class="text-blue-500 px-4 hover:underline font-medium text-sm" wire:click="$set('open', true)">
        Observaciones
    </button>

    <x-dialog-modal wire:model="open" class="bg-gray-50">
        <div class="max-w-4xl mx-auto">
            <x-slot name="title">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
                    <h2 class="text-xl font-semibold">
                        Observaciones: {{$invitacion->proveedor->razonsocial}}
                    </h2>
                </div>
            </x-slot>

            <x-slot name="content" class="p-6">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden p-6">
                    @if(($concurso->user_id === auth()->id() || auth()->user()->can('Concursos/Concursos/Editar')) && $concurso->estado_id == 3)
                        <div class="mb-4">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                Ingrese las observaciones o cotización (este campo solo es editable en estado de Análisis)
                            </label>
                            <textarea 
                                id="observaciones" 
                                wire:model.defer="observaciones" 
                                rows="4" 
                                class="w-full border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                placeholder="Ej: Cotización $100.000, no presentó planilla técnica, etc."
                            ></textarea>
                        </div>
                    @else
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Observaciones</h3>
                            <div class="p-4 bg-gray-100 rounded-md text-gray-800 italic">
                                {{ $invitacion->observaciones ?? 'Sin observaciones' }}
                            </div>
                            @if($concurso->estado_id != 3)
                                <p class="text-xs text-red-500 mt-2">
                                    Las observaciones solo pueden editarse cuando el concurso está en Análisis.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex justify-end space-x-3">
                    <x-secondary-button wire:click="$set('open', false)" wire:loading.attr="disabled">
                        Cerrar
                    </x-secondary-button>

                    @if(($concurso->user_id === auth()->id() || auth()->user()->can('Concursos/Concursos/Editar')) && $concurso->estado_id == 3)
                        <x-button wire:click="save" wire:loading.attr="disabled" class="bg-blue-600 hover:bg-blue-700 text-white">
                            Guardar Cambios
                        </x-button>
                    @endif
                </div>
            </x-slot>
        </div>
    </x-dialog-modal>
</div>
