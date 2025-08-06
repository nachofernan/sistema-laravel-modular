<div>
    <button class="inline-flex items-center px-2.5 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded transition-colors" wire:click="$set('open', true)"> 
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Editar
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            <div class="flex items-center">
                <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Editar {{ $apoderado->tipo == 'representante' ? 'Representante Legal' : 'Apoderado' }}
            </div>
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.apoderados.update', $apoderado)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="space-y-4">
                    @if ($apoderado->tipo == 'representante')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                            <input type="text" name="nombre" value="{{$apoderado->nombre}}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Archivo</label>
                            <input type="file" name="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vencimiento</label>
                            <input type="date" name="vencimiento" 
                                    value="{{$apoderado->documentos()->orderBy('id', 'desc')->first()->vencimiento ? $apoderado->documentos()->orderBy('id', 'desc')->first()->vencimiento->format('Y-m-d') : ''}}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endif
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="activo" @checked($apoderado->activo) class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">
                            Apoderado activo
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end pt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                        Editar Apoderado
                    </button>
                </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
