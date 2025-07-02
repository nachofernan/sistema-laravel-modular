<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-8 titulo-index">
                        Listado Áreas
                    </div>
                    <div class="col-span-2 text-center">
                        @can('Usuarios/Areas/Crear')
                        <a href="{{route('usuarios.areas.create')}}" class="block w-full text-sm py-2 boton-celeste">Nueva Área</a>
                        @endcan
                    </div>
                </div>
                <table class="table-index">
                    <thead>
                        <tr>
                            <th class="th-index">
                                ID
                            </th>
                            <th class="th-index">
                                Nombre
                            </th>
                            <th class="th-index">
                                Personal
                            </th>
                            <th class="th-index">
                                
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @livewire('usuarios.areas.foreach-table-tr', ['areas' => $areas, 'nivel' => ''])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
