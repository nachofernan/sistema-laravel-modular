<div>
    {{-- Success is as dangerous as failure. --}}
    <div class="w-full text-right">
        <button wire:click="$set('open', true)" class="text-xs link-azul">
            Editar
        </button>
    </div>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="text-left border-b pb-2">
                Editar Pregunta
            </div>
        </x-slot>
        <x-slot name="content">
            <form action="{{ route('capacitaciones.preguntas.update', $pregunta) }}" method="POST">
                @csrf
                @method('put')
                <div class="grid grid-cols-10 gap-6">
                    <div class="col-span-3 atributo-edit">
                        Pregunta
                    </div>
                    <div class="col-span-7">
                        <input type="text" class="input-full" name="pregunta" value="{{$pregunta->pregunta}}" required>
                    </div>
                    <div class="col-span-3 atributo-edit">
                        Tipo de respuesta
                    </div>
                    <div class="col-span-7">
                        <select name="con_opciones" class="input-full">
                            <option value="0" @selected($pregunta->con_opciones == 0)>Texto Libre</option>
                            <option value="1" @selected($pregunta->con_opciones == 1)>Con Opciones</option>
                        </select>
                    </div>
                    <div class="col-span-10 text-right">
                        <button type="submit" class="boton-celeste">Editar Pregunta</button>
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
