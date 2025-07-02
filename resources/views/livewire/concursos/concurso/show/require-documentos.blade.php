<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 mt-4">
        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-700">Contenido de la Oferta a Presentar</h2>
            @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
            @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
            <button class="link-azul float-right text-sm mt-2 mr-4" wire:click="$set('open', true)"> 
                Seleccionar
            </button>
            @endif
            @endif
        </div>
    
        <div class="divide-y divide-gray-200">
            @forelse ($concurso->documentos_requeridos as $documento_requerido)
            <div class="px-6 py-2 text-sm">
                - {{$documento_requerido->nombre}}
            </div>
            @empty
                <div class="px-6 py-4 text-center text-gray-500">
                    Seleccione uno o más documentos para solicitar
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
