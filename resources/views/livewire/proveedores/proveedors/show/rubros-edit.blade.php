<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
            <h3 class="font-medium text-gray-700">
                Rubros y Subrubro
            </h3>
            <button class="hover:underline text-blue-600 text-sm" wire:click="$set('open', true)"> 
                Editar listado
            </button> 
        </div>
        <div class="text-sm p-4">
            @foreach ($proveedor->subrubros as $subrubro)
                @if (!isset($rubro_id) || $rubro_id != $subrubro->rubro->id)
                    @php
                        $rubro_id = $subrubro->rubro->id;
                    @endphp
                    <div class="grid grid-cols-12 gap-3 px-3">
                        <div class="col-span-1"></div>
                        <div class="col-span-11 font-bold">{{ $subrubro->rubro->nombre }}</div>
                    </div>
                @endif
                <div class="grid grid-cols-12 gap-3 px-3">
                    <div class="col-span-3"></div>
                    <div class="col-span-9">{{ $subrubro->nombre }}</div>
                </div>
            @endforeach
        </div>
    </div>
    <x-dialog-modal wire:model="open" maxWidth="7xl"> 
        <x-slot name="title"> 
            <div class="border-b py-2 grid grid-cols-2"> 
                <div class="font-bold col">
                    Editar Rubros y Subrubros
                </div>
                <div class="col">
                    <input type="text" wire:model.live="search" class="input-full" placeholder="Buscar">
                </div>
            </div>                 
                
                
        </x-slot> 
        <x-slot name="content">
            @foreach ($resultados as $resultado)
            <div class="grid grid-cols-12 gap-6 border-b py-2"> 
                <div class="col-span-3 font-bold">
                    {{ $resultado['rubro']->nombre }}
                    <span wire:click="marcarTodos({{$resultado['rubro']->id}})" class="text-sm cursor-pointer text-blue-600 hover:underline mx-2">Todos</span>
                </div>
                <div class="col-span-9">
                    @foreach ($resultado['subrubros'] as $subrubro)
                        <div wire:click="agregarSubrubro({{$subrubro->id}})">
                            <input type="checkbox" class="border-gray-400 rounded mb-1" @checked($proveedor->subrubros->contains($subrubro->id))>
                            {{ $subrubro->nombre }}
                        </div>
                    @endforeach
                </div>
            </div>                
            @endforeach
           {{--  @foreach ($rubros as $rubro)
            <div class="grid grid-cols-12 gap-6 border-b py-2"> 
                <div class="col-span-3 font-bold">
                    {{ $rubro->nombre }}
                </div>
                <div class="col-span-9">
                    @foreach ($rubro->subrubros as $subrubro)
                        <div wire:click="agregarSubrubro({{$subrubro->id}})">
                            <input type="checkbox" class="border-gray-400 rounded mb-1" @checked($proveedor->subrubros->contains($subrubro->id))>
                            {{ $subrubro->nombre }}
                        </div>
                    @endforeach
                </div>
            </div>                
            @endforeach --}}
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
