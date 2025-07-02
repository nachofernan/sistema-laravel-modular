<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <button wire:click="$set('open', true)" class="link-azul text-red-700"> 
        Eliminar
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title">Eliminar Usuario</x-slot> 
        <x-slot name="content" class="text-sm"> 
            <h1 class="h1 border-b border-gray-300 text-xl text-center">
                {{$user->realname}}
                <div class="text-xs">{{$user->name}}</div>
                <div class="text-xs">Esta acción eliminará al usuario de la visualización general y acceso al sistema (Soft Delete), y podrá ser reactivado mediante acceso a la base de datos.</div>
            </h1>
            <div class="grid grid-cols-12 gap-6 px-5 py-8"> 
                <div class="col-span-4 text-center col-start-5">
                    <button wire:click="delete_user()" class="w-full text-lg py-2 bg-red-500 text-white rounded-lg">Eliminar Usuario</button>
                </div>
            </div>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
