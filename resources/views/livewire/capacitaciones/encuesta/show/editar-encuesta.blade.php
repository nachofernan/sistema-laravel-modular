<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="w-full text-right">
        <button wire:click="$set('open', true)" class="boton-celeste">
            Editar
        </button>
    </div>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="text-left border-b pb-2">
                Editar Encuesta
            </div>
        </x-slot>
        <x-slot name="content">
            <form action="{{ route('capacitaciones.encuestas.update', $encuesta) }}" method="POST">
                @csrf
                @method('put')
                <div class="grid grid-cols-10 gap-6">
                    <div class="col-span-2 atributo-edit">
                        Nombre
                    </div>
                    <div class="col-span-8">
                        <input type="text" class="input-full" name="nombre" value="{{$encuesta->nombre}}" required>
                    </div>
                    <div class="col-span-2 atributo-edit">
                        Descripción
                    </div>
                    <div class="col-span-8">
                        <textarea name="descripcion" rows="10" class="input-full">{!! nl2br($encuesta->descripcion) !!}</textarea>
                    </div>
                    <div class="col-span-2 atributo-edit">
                        Estado
                    </div>
                    <div class="col-span-8">
                        <select name="estado" class="input-full">
                            <option value="0" @selected($encuesta->estado == 0)>Inactiva</option>
                            @if ($encuesta->preguntas->count())
                                <option value="1" @selected($encuesta->estado == 1)>Activa</option>
                                <option value="2" @selected($encuesta->estado == 2)>Finalizada</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-span-10 text-right">
                        <button type="submit" class="boton-celeste">Editar Encuesta</button>
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
