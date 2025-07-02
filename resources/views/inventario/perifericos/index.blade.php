<x-app-layout>
    <div class="w-full xl:w-5/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                    <div class="grid grid-cols-10 py-4">
                        <div class="col-span-6 titulo-index">
                            Listado Periféricos
                        </div>
                        <div class="col-span-4 text-center">
                            @can('Inventario/Perifericos/Crear')
                            @livewire('inventario.perifericos.index.crear', [], key(microtime(true)))
                            @endcan
                        </div>
                    </div>
                    <table class="table-index">
                        <thead>
                            <tr>
                                <th class="th-index">
                                    Periférico
                                </th>
                                <th class="th-index text-center">
                                    Stock
                                </th>
                                <th class="th-index text-center">
                                    Editar
                                </th>
                            </tr>
                        </thead>
                
                        <tbody>
                            @foreach ($perifericos as $periferico)
                                <tr class="hover:bg-gray-300">
                                    <td class="td-index">
                                        {{ $periferico->nombre }}
                                    </td>
                                    <td class="td-index text-center">
                                        {{ $periferico->stock }}
                                    </td>
                                    <td class="td-index text-center">
                                        @can('Inventario/Perifericos/Editar')
                                        @livewire('inventario.perifericos.index.editar', ['periferico' => $periferico], key($periferico->id.microtime(true)))
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                
            </div>
        </div>
    </div>
</x-app-layout>