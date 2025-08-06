<div>
    <button class="inline-flex items-center px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors" wire:click="$set('open', true)"> 
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo Documento
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Cargar Nuevo Documento
            </div>
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.documentos.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Archivo</label>
                        <input type="file" name="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento</label>
                        <select name="documento_tipo_id" wire:model.live="documento_tipo_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Seleccionar tipo de documento</option>
                            @foreach ($documentoTipos as $documentoTipo)
                                <option value="{{$documentoTipo->id}}">{{$documentoTipo->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    @if($requiere_vencimiento)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vencimiento</label>
                            <input type="date" name="vencimiento" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    @endif
                </div>
                
                <div class="flex justify-end pt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                        Cargar Documento
                    </button>
                </div>
            </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>