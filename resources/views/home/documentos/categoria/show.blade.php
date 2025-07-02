<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="titulo-index">
                    {{$categoria->nombre}}
                </div>
                <div class="w-full mb-12 xl:mb-0 px-4 mx-auto mt-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
                @foreach ($categoria->hijos as $categoria_hijo)
                        <div class="grid grid-cols-12 cabecera-show">
                            <div class="col-span-12">
                                <div class="titulo-show">
                                    {{ $categoria_hijo->nombre }}
                                </div>
                            </div>
                        </div>
                        <div class="block w-full overflow-x-auto py-5 px-5">
                            <table class="table-index">
                                <thead>
                                    <th class="th-index">
                                        Nombre
                                    </th>
                                    <th class="th-index">
                                        Descripción
                                    </th>
                                    <th class="th-index">
                                        Descargar
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($categoria_hijo->documentosVisibles as $documento)
                                        <tr class="hover:bg-gray-200">
                                            <td class="td-index">
                                                {{$documento->nombre}}
                                            </td>
                                            <td class="td-index">
                                                {{$documento->descripcion}}
                                            </td>
                                            <td class="td-index">
                                                <a href="{{route('home.documentos.download', $documento)}}" target="_blank" class="link-azul">Descargar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>