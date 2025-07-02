{{-- <x-app-layout>
    <div class="w-full xl:w-11/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-2">
                    <div class="col-span-7 titulo-index">
                        Listado Rubros y Subrubros
                    </div>
                    <div class="col-span-3">
                        <small class="ml-24 font-normal text-xs">Recargue la página para ver los cambios</small>
                    </div>
                </div>
                <div class="items-center bg-transparent w-full border-collapse bg-white shadow-lg rounded py-4">
                    @foreach($rubros as $rubro)
                    <div class="hover:bg-gray-50 py-4">
                        <div class="grid grid-cols-12 gap-3 px-3 pb-1 border-b"> 
                            <div class="col-span-5 font-bold flex items-baseline gap-4 justify-end">
                                @livewire('proveedores.rubros.edit-rubro', ['rubro' => $rubro], key($rubro->id))
                                <div>{{$rubro->nombre}}</div>
                            </div>
                            <div class="col-span-7"></div>
                        </div>
                        @foreach($rubro->subrubros as $subrubro)
                        <div class="grid grid-cols-12 gap-3 px-3 text-sm"> 
                            <div class="col-span-5 text-right"></div>
                            <div class="col-span-7 flex items-baseline gap-4">
                                <div>{{$subrubro->nombre}}</div>
                                @livewire('proveedores.rubros.edit-subrubro', ['subrubro' => $subrubro], key($subrubro->id))
                            </div>
                        </div>
                        @endforeach
                        <div class="grid grid-cols-12 gap-3 px-3 py-1"> 
                            <div class="col-span-5 text-right"></div>
                            <div class="col-span-7 ">
                                @livewire('proveedores.rubros.create-subrubro', ['rubro' => $rubro], key($rubro->id))
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
<x-app-layout>
    <div class="w-full xl:w-11/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            @livewire('proveedores.rubros.rubros-manager')
        </div>
    </div>
</x-app-layout>