<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8 pt-1">
                    <div class="titulo-show">
                        {{$documento->nombre}} - ({{$documento->categoria->nombre}})
                    </div>
                </div>
                <div class="col-span-4 text-right pt-1">
                    @can('Documentos/Documentos/Editar')
                    <a href="{{route('documentos.documentos.edit', $documento)}}" class="text-sm boton-celeste">Editar</a>
                    @endcan
                </div>
            </div>

            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="grid grid-cols-6 gap-4">
                    <div class="col-span-3">
                        <div class="text-lg border-b pb-2 mb-2">
                            Datos del Documento
                        </div>
                        <div class="grid grid-cols-6 gap-4 text-sm">
                            <div class="col-span-2 text-right">
                                Nombre:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->nombre}}
                            </div>
                            <div class="col-span-2 text-right">
                                Descripción:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->descripcion}}
                            </div>
                            <div class="col-span-2 text-right">
                                Versión:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->version ?? 'Sin Versión'}}
                            </div>
                            <div class="col-span-2 text-right">
                                Sede:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->sede->nombre ?? 'Todas las Sedes'}}
                            </div>
                            <div class="col-span-2 text-right">
                                Orden:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->orden}}
                            </div>
                            <div class="col-span-2 text-right">
                                Visible:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->visible}}
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <div class="text-lg border-b pb-2 mb-2">
                            Descargas: {{count($documento->descargas)}}
                        </div>
                        <div class="grid grid-cols-6 gap-4 text-sm">
                            <div class="col-span-2 text-right">
                                Usuario Creador:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->user->realname}}
                            </div>
                            <div class="col-span-2 text-right">
                                Cargado:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->archivo_uploaded_at ? Carbon\Carbon::create($documento->archivo_uploaded_at)->format('d-m-Y H:i') : 'No Registrado'}}
                            </div>
                            <div class="col-span-2 text-right">
                                Archivo:
                            </div>
                            <div class="col-span-4 font-bold">
                                <a href="{{route('documentos.documentos.download', $documento)}}" target="_blank" class="link-azul">
                                {{$documento->archivo}}
                                </a>
                            </div>
                            <div class="col-span-2 text-right">
                                Tipo - Extensión:
                            </div>
                            <div class="col-span-4 font-bold">
                                {{$documento->mimeType}} - {{$documento->extension}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>