<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="text-right">
        <button wire:click="$set('open', true)" class="link-azul text-xs"> 
            Editar Permiso
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"></x-slot> 
        <x-slot name="content" class="text-sm"> 
            <h1 class="h1 pb-2 border-b border-gray-300 text-xl text-center">
                Editar Permiso: <strong>{{$permiso->nombre}}</strong>
            </h1>
            <div class="grid grid-cols-12 gap-3 px-5 py-3 border-b border-gray-300  text-left"> 
                <div class="col-span-3 text-right font-bold text-xl pt-2">
                    {{ucfirst($modulo->nombre)}}/
                </div>
                <div class="col-span-6 font-bold">
                    <input type="text" wire:model="nombre" class="rounded w-full border-b" placeholder="Nuevo Permiso">
                </div>
                <div class="col-span-3 text-center">
                    <button wire:click="create()" class="boton-celeste w-full py-2 mt-1">Editar Permiso</button>
                </div>
            </div>
            <div class="px-5 py-3 text-left">
                <p>La nomeclatura que se utiliza para crear Roles es la siguiente:</p>
                <p class="ml-10 py-2"><strong>Modulo</strong>/<strong>Modelo(s)</strong>/<strong>Rol</strong></p>
                <p>La nomeclatura que se utiliza para crear Permisos es la siguiente:</p>
                <p class="ml-10 py-2"><strong>Modulo</strong>/<strong>Modelo</strong>/<strong>Acción</strong></p>
                <p>Se pueden agrupar Modelos para un mismo Rol, pero cada permiso es individual al modelo.</p>
                <p>El nombre del módulo no hay que escribirlo.</p>
            </div>
        </x-slot> 
        <x-slot name="footer">
            <span class="mt-3">Ingrese 1234 para borrar</span>
            <input type="text" wire:model="test" class="rounded mx-2">
            <button wire:click="delete()" class="boton-celeste text-white bg-red-600 hover:bg-red-800">
                Borrar Permiso
            </button>
        </x-slot> 
    </x-dialog-modal> 
</div>
