<div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Rubros y Subrubros</h3>
            </div>
            <button class="inline-flex items-center px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded transition-colors" wire:click="$set('open', true)"> 
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar Listado
            </button> 
        </div>
        <div class="px-6 py-4">
            @foreach ($proveedor->subrubros as $subrubro)
                @if (!isset($rubro_id) || $rubro_id != $subrubro->rubro->id)
                    @php
                        $rubro_id = $subrubro->rubro->id;
                    @endphp
                    <div class="font-medium text-gray-900 mb-2">{{ $subrubro->rubro->nombre }}</div>
                @endif
                <div class="text-sm text-gray-600 ml-4 mb-1">{{ $subrubro->nombre }}</div>
            @endforeach
        </div>
    </div>
    
    <x-dialog-modal wire:model="open" maxWidth="7xl"> 
        <x-slot name="title"> 
            <div class="border-b py-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="font-bold">Editar Rubros y Subrubros</span>
                    </div>
                    <div>
                        <input type="text" wire:model.live="search" class="px-3 py-2 w-96 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar">
                    </div>
                </div>
            </div>                 
        </x-slot> 
        <x-slot name="content">
            @foreach ($resultados as $resultado)
                <div class="border-b border-gray-200 py-3 last:border-b-0"> 
                    <div class="flex items-center mb-2">
                        <div class="font-bold text-gray-900">
                            {{ $resultado['rubro']->nombre }}
                        </div>
                        <button wire:click="marcarTodos({{$resultado['rubro']->id}})" class="ml-5 text-xs text-blue-600 hover:text-blue-800 hover:underline">
                            Seleccionar Todos
                        </button>
                    </div>
                    <div class="space-y-1">
                        @foreach ($resultado['subrubros'] as $subrubro)
                            <div wire:click="agregarSubrubro({{$subrubro->id}})" class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded">
                                <input type="checkbox" class="h-3 w-3 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" @checked($proveedor->subrubros->contains($subrubro->id))>
                                <span class="ml-2 text-xs text-gray-700">{{ $subrubro->nombre }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>                
            @endforeach
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
