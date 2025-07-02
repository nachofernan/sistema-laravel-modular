<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8 pt-1">
                    <div class="titulo-show">
                        {{ $categoria->nombre }}
                    </div>
                </div>
                <div class="col-span-4 text-right">
                    {{-- <button class="text-sm boton-celeste">Editar</button> --}}
                    @can('Documentos/Documentos/Editar')
                    @livewire('documentos.categorias.show.edit', ['categoria' => $categoria], key($categoria->id))
                    @endcan
                </div>
            </div>

            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="py-4 mb-2 pl-3">
                    Listado Documentos
                </div>
                <table class="items-center bg-transparent w-full border-collapse bg-white shadow-lg rounded ">
                    <thead>
                        <tr>
                            <th
                                class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Orden
                            </th>
                            <th
                                class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Documento
                            </th>
                            <th
                                class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Descripción
                            </th>
                            <th
                                class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Descargas
                            </th>
                            <th
                                class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Sede
                            </th>
                            <th
                                class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                Archivo
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categoria->documentos as $documento)
                            <tr class="hover:bg-gray-300">
                                <td
                                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 ">
                                    {{ $documento->orden }}
                                </td>
                                <td
                                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 ">
                                    {{ $documento->nombre }}
                                </td>
                                <td
                                    class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ $documento->descripcion }}
                                </td>
                                <td
                                    class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ $documento->descargas->count() }}
                                </td>
                                <td
                                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ $documento->sede->nombre ?? 'Sin Sede' }}
                                </td>
                                <td
                                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{-- <a href="{{Storage::disk('public')->url('../public/storage/'.$documento->file_storage)}}" target="_blank">Descargar</a> -  --}}
                                    <a href="{{ route('documentos.documentos.download', $documento) }}"
                                        target="_blank">Descargar</a>
                                    {{-- {{$documento->archivo}} --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
