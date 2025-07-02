<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="text-right">
        <button class="link-azul text-sm" type="submit" wire:click="$set('open', true)"> 
            Editar
        </button> 
    </div>
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            Editar Representante Legal
        </x-slot> 
        <x-slot name="content">
            <form action="{{route('proveedores.apoderados.update', $apoderado)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="grid grid-cols-10 gap-4">
                    @if ($apoderado->tipo == 'representante')
                        <div class="col-span-2 text-right mt-2">
                            Nombre
                        </div>
                        <div class="col-span-8">
                            <input type="text" name="nombre" value="{{$apoderado->nombre}}" class="input-full">
                        </div>
                        <div class="col-span-2 text-right mt-2">
                            Archivo
                        </div>
                        <div class="col-span-8">
                            <input type="file" name="file" class="input-full">
                        </div>
                        <div class="col-span-2 text-right mt-2">
                            Vencimiento
                        </div>
                        <div class="col-span-8">
                            <input type="date" name="vencimiento" 
                                    value="{{$apoderado->documentos()->orderBy('id', 'desc')->first()->vencimiento ? $apoderado->documentos()->orderBy('id', 'desc')->first()->vencimiento->format('Y-m-d') : ''}}" 
                                    class="input-full">
                        </div>
                    @endif
                </div>
                <div class="pt-4 flex justify-between">
                    <div class="mt-2 ml-5">
                            Apoderado activo
                            <input type="checkbox" name="activo" @checked($apoderado->activo) class="ml-2 rounded" />
                    </div>
                    <button type="submit" class="boton-celeste">Editar Apoderado</button>
                </div>
                </form>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
