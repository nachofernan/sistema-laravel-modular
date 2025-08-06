<div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Contenido de la Oferta a Presentar</h3>
            </div>
            @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                    <button class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition-colors" wire:click="$set('open', true)"> 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1">
                            <path d="M8 4a.75.75 0 0 1 .75.75v2.5h2.5a.75.75 0 0 1 0 1.5h-2.5v2.5a.75.75 0 0 1-1.5 0v-2.5h-2.5a.75.75 0 0 1 0-1.5h2.5v-2.5A.75.75 0 0 1 8 4Z" />
                        </svg>
                        Seleccionar
                    </button>
                @endif
            @endif
        </div>

        <div class="px-6 py-4">
            @forelse ($concurso->documentos_requeridos as $documento_requerido)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{$documento_requerido->nombre}}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay documentos requeridos</h3>
                    <p class="text-gray-500">Seleccione uno o más documentos para solicitar.</p>
                </div>
            @endforelse  
        </div>
    </div>
        <x-dialog-modal wire:model="open"> 
            <div class="max-w-10xl">
            <x-slot name="title"> 
                <div class="border-b py-2"> 
                    <div class="font-bold mt-1">
                        Contenido de la Oferta a Presentar
                    </div>
                </div>                 
            </x-slot> 
            <x-slot name="content">
                <table class="w-full">
                    <thead>
                        <th class="text-left">Tipo de Documento</th>
                        <th>Requerido</th>
                    </thead>
                    <tbody>
                        @foreach ($documento_tipos as $documento_tipo)
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-2">{{$documento_tipo->nombre}}</td>
                            <td class="text-center py-2">
                                <input 
                                    type="checkbox" 
                                    wire:click="updateDT({{$documento_tipo->id}})" 
                                    @disabled($documento_tipo->obligatorio)
                                    @checked($concurso->documentos_requeridos->contains($documento_tipo->id)) />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-slot> 
            <x-slot name="footer">
            </x-slot> 
            </div>
        </x-dialog-modal>     
</div>
