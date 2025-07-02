<div>
    {{-- The whole world belongs to you. --}}
    <div class="w-full text-right">
        <button wire:click="$set('open', true)" class="text-sm link-azul">
            Agregar Documento
        </button>
    </div>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="text-left border-b pb-2">
                Agregar Nuevo Documento
            </div>
        </x-slot>
        <x-slot name="content">
            <form action="{{ route('capacitaciones.documentos.store') }}" method="POST" enctype="multipart/form-data">
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
                        Archivo
                    </div>
                    <div class="col-span-8">
                        <input type="file" class="input-full" name="file" required>
                    </div>
                    <div class="col-span-10 text-right">
                        <button type="submit" class="boton-celeste">Guardar Documento</button>
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
