<x-app-layout>
    <div class="border-b border-gray-200 rounded-lg bg-gray-200 mx-auto w-3/4 px-0 py-5 mt-10 text-sm">
        <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
            <div class="col-span-8 pt-1">
                <div class="titulo-show">
                    Editar Proveedor
                </div>
            </div>
            <div class="col-span-4 text-right text-sm">
                <button type="submit" class="boton-celeste">Guardar</button>
                <a href="{{ route('proveedores.proveedors.show', $proveedor) }}">
                    <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                </a>
            </div>
        </div>
        <div class="grid grid-cols-10">
            <div class="col-span-10 pt-2">
                <div class="text-lg border-b border-gray-300 mb-1 px-5 pt-2">
                    Listado de Rubros y Subrubros
                </div>
                @foreach($rubros as $rubro)
                <div class="grid grid-cols-12 gap-3 px-3 py-1"> 
                    <div class="col-span-5 text-right">{{$rubro->nombre}}</div>
                    <div class="col-span-7 font-bold"></div>
                </div>
                @foreach($rubro->subrubros as $subrubro)
                <div class="grid grid-cols-12 gap-3 px-3 py-1"> 
                    <div class="col-span-5 text-right"></div>
                    <div class="col-span-7 font-bold">{{$subrubro->nombre}}</div>
                </div>
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>