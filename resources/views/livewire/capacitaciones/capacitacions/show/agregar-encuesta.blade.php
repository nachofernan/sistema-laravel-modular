<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div class="w-full text-right">
        <button wire:click="$set('open', true)" class="text-sm link-azul">
            Agregar Encuesta
        </button>
    </div>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="text-left border-b pb-2">
                Agregar Nueva Encuesta
            </div>
        </x-slot>
        <x-slot name="content">
            <form action="{{ route('capacitaciones.encuestas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="capacitacion_id" value="{{ $capacitacion->id }}">
                <div class="grid grid-cols-10 gap-6">
                    <div class="col-span-2 atributo-edit">
                        Nombre
                    </div>
                    <div class="col-span-8">
                        <input type="text" class="input-full" name="nombre" required>
                    </div>
                    <div class="col-span-2 atributo-edit">
                        Descripción
                    </div>
                    <div class="col-span-8">
                        <textarea name="descripcion" rows="10" class="input-full"></textarea>
                    </div>
                    <div class="col-span-10 text-right">
                        <button type="submit" class="boton-celeste">Crear Encuesta</button>
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
