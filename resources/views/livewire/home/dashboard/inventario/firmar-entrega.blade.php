<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <button wire:click="$set('open', true)" class="px-2 py-1 text-xs bg-blue-300 rounded">
        Validar 
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title">Validar Entrega</x-slot> 
        <x-slot name="content"> 
            <p>Por la siguiente manifiesto tener posesión del elemento siguiente:</p>
            {{$elemento->codigo}} - {{$elemento->categoria->nombre}}
        </x-slot> 
        <x-slot name="footer"> 
            <div class="text-center">
                <button wire:click="firmar()" class="px-6 py-3 bg-blue-600 text-white rounded font-bold">FIRMAR</button>
            </div>
        </x-slot> 
    </x-dialog-modal> 
</div>
