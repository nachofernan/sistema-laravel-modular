<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <button class="link-azul float-right text-sm mt-2 mr-4" wire:click="$set('open', true)"> 
        Editar
    </button>
    <x-dialog-modal wire:model="open"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="border-b py-2"> 
                <div class="font-bold">
                    Editar Rubros y Subrubros
                </div>
            </div>                 
                
                
        </x-slot> 
        <x-slot name="content">
            <div class="mb-4">
                <label for="rubro" class="block text-sm font-medium text-gray-700">Rubro</label>
                <select wire:model.live="selectedRubro" id="rubro" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Seleccione un rubro</option>
                    @foreach($rubros as $rubro)
                        <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                    @endforeach
                </select>
            </div>

            @if($subrubros)
                <div class="mb-4">
                    <label for="subrubro" class="block text-sm font-medium text-gray-700">Subrubro</label>
                    <select wire:model.live="selectedSubrubro" id="subrubro" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Seleccione un subrubro</option>
                        @foreach($subrubros as $subrubro)
                            <option value="{{ $subrubro->id }}">{{ $subrubro->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($showSaveButton)
                <button wire:click="save" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Guardar
                </button>
            @endif
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
        </div>
    </x-dialog-modal> 
</div>
