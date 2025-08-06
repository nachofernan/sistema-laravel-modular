<div>
    <button class="inline-flex items-center px-2.5 py-1 {{ $contexto == 'representante' ? 'bg-purple-600 hover:bg-purple-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-xs font-medium rounded transition-colors" wire:click="$set('open', true)"> 
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo {{ $contexto == 'representante' ? 'Representante Legal' : 'Apoderado' }}
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            <div class="flex items-center">
                <svg class="h-5 w-5 {{ $contexto == 'representante' ? 'text-purple-500' : 'text-indigo-500' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Cargar Nuevo {{ $contexto == 'representante' ? 'Representante Legal' : 'Apoderado' }}
            </div>
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.apoderados.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
                    <input type="hidden" name="tipo" value="{{$contexto}}">
                    
                    @if ($contexto == 'representante')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                            <input type="text" name="nombre" value="{{$proveedor->razonsocial}}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vencimiento</label>
                            <input type="date" name="vencimiento" value="{{now()->addYear()->format('Y-m-d')}}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Archivo</label>
                        <input type="file" name="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>
                
                <div class="flex justify-end pt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                        Guardar {{ $contexto == 'representante' ? 'Representante Legal' : 'Apoderado' }}
                    </button>
                </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
