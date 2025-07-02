<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="w-full text-right mt-2">
        <button wire:click="$set('open', true)" class="text-sm link-azul">
            Agregar Pregunta
        </button>
    </div>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="text-left border-b pb-2">
                Agregar Nueva Pregunta
            </div>
        </x-slot>
        <x-slot name="content">
            <form action="{{ route('capacitaciones.preguntas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">
                <div class="grid grid-cols-10 gap-6">
                    <div class="col-span-3 atributo-edit">
                        Pregunta
                    </div>
                    <div class="col-span-7">
                        <input type="text" class="input-full" name="pregunta" required>
                    </div>
                    <div class="col-span-3 atributo-edit">
                        Tipo de respuesta
                    </div>
                    <div class="col-span-7">
                        <select name="con_opciones" class="input-full">
                            <option value="0">Texto Libre</option>
                            <option value="1">Con Opciones</option>
                        </select>
                    </div>
                    <div class="col-span-10 text-right">
                        <button type="submit" class="boton-celeste">Crear Pregunta</button>
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
