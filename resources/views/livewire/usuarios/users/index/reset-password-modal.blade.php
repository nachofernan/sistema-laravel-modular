<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <button wire:click="$set('open', true)" class="link-azul"> 
        {{$pass_set ? "¡Actualizada!" : "Resetear Contraseña"}}
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title">Resetear Contraseña</x-slot> 
        <x-slot name="content" class="text-sm"> 
            <h1 class="h1 border-b border-gray-300 text-xl text-center">
                {{$user->realname}}
                <div class="text-xs">{{$user->name}}</div>
            </h1>
            <div class="grid grid-cols-12 gap-6 px-5 py-8"> 
                <div class="col-span-7 font-bold">
                    <input type="text" wire:model="nueva_password" class="rounded w-full border-b" placeholder="Nueva Contraseña">
                    @if ($error)
                    <p class="text-sm text-red-600">{{$error}}</p>
                    @endif
                </div>
                <div class="col-span-1"></div>
                <div class="col-span-4 text-center">
                    <button wire:click="reset_password()" class="w-full text-lg py-2 bg-blue-500 text-white rounded-lg">Actualizar</button>
                </div>
            </div>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
