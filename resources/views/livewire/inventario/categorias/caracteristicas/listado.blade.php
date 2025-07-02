<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="pb-4">
        <div class="subtitulo-show ">
            Características:
        </div>
        <table class="table-index">
            <thead>
                <th class="th-index">Nombre</th>
                <th class="th-index text-center">Visible</th>
            </thead>
            <tbody>
                @foreach ($categoria->caracteristicas as $caracteristica)
                    @livewire('inventario.categorias.caracteristicas.table-row', ['caracteristica' => $caracteristica], key($caracteristica->id . microtime(true)))
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        @can('Inventario/Inventario/Editar')
        <div class="subtitulo-show">Agregar característica:</div>
        <input type="text" wire:model="caracteristica_nueva" class="input-full" placeholder="Nombre">
        <button wire:click="guardar()" class="boton-celeste w-full mt-1">Agregar</button>
        @endcan
    </div>
</div>
