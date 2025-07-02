<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8">
                    <div class="titulo-show">
                        {{ $capacitacion->nombre }}
                    </div>
                    <div class="text-xs">
                        {{ Carbon\Carbon::create($capacitacion->fecha)->format('d-m-Y') }}
                    </div>
                </div>
                <div class="col-span-4 text-right pt-3 text-sm">
                    @can('Capacitaciones/Capacitaciones/Editar')
                        <a href="{{ route('capacitaciones.capacitacions.edit', $capacitacion) }}" class="boton-celeste">Editar</a>
                    @endcan
                </div>
            </div>
            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="grid grid-cols-2 gap-5">
                    <div class="col">
                        <div class="subtitulo-show">
                            Datos Generales
                        </div>
                        <div class="grid-datos-show">
                            <div class="atributo-show">
                                Nombre
                            </div>
                            <div class="valor-show">
                                {{ $capacitacion->nombre }}
                            </div>
                            <div class="atributo-show">
                                Fecha
                            </div>
                            <div class="valor-show">
                                {{ Carbon\Carbon::create($capacitacion->fecha)->format('d-m-Y') }}
                            </div>
                            <div class="atributo-show">
                                Descripción
                            </div>
                            <div class="valor-show">
                                {!! nl2br($capacitacion->descripcion) !!}
                            </div>
                        </div>

                        <div class="subtitulo-show grid grid-cols-2 pt-2 mt-2">
                            <div class="col">
                                Documentos
                            </div>
                            <div class="col">
                                @can('Capacitaciones/Capacitaciones/Editar')
                                @livewire('capacitaciones.capacitacions.show.agregar-documento', ['capacitacion' => $capacitacion], key($capacitacion->id . microtime(true)))
                                @endcan
                            </div>
                        </div>
                        @foreach ($capacitacion->documentos as $documento)
                            <div class="grid grid-cols-12 gap-x-3 gap-y-2 text-sm border-b pb-2 mb-2">
                                <div class="col-span-2 font-bold">
                                    Nombre
                                </div>
                                <div class="col-span-10">
                                    {{ $documento->nombre }}
                                </div>
                                <div class="col-span-2 font-bold">
                                    Archivo
                                </div>
                                <div class="col-span-6">
                                    {{ $documento->archivo }}
                                </div>
                                <div class="col-span-2 text-right text-xs">
                                    <a href="{{ Storage::disk('capacitaciones')->url($documento->file_storage) }}"
                                        target="_blank" class="link-azul">Descargar</a>
                                </div>
                                <div class="col-span-2 text-right text-xs">
                                    <form action="{{ route('capacitaciones.documentos.destroy', $documento) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <input type="submit" class="link-azul text-red-600 cursor-pointer"
                                            value="Eliminar">
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <div class="subtitulo-show grid grid-cols-2 pt-2 mt-2">
                            <div class="col">
                                Encuestas
                            </div>
                            <div class="col">
                                @can('Capacitaciones/Encuestas/Crear')
                                @livewire('capacitaciones.capacitacions.show.agregar-encuesta', ['capacitacion' => $capacitacion], key($capacitacion->id . microtime(true)))
                                @endcan
                            </div>
                        </div>
                        @foreach ($capacitacion->encuestas as $encuesta)
                            <div class="grid grid-cols-12 gap-x-3 gap-y-2 text-sm border-b pb-2 mb-2">
                                <div class="col-span-2 font-bold">
                                    Nombre
                                </div>
                                <div class="col-span-10">
                                    @can('Capacitaciones/Encuestas/Ver')
                                    <a href="{{ route('capacitaciones.encuestas.show', $encuesta) }}" class="link-azul">
                                        {{ $encuesta->nombre }}
                                    </a>
                                    @else
                                    {{ $encuesta->nombre }}
                                    @endcan
                                </div>
                                <div class="col-span-2 font-bold">
                                    Descripción
                                </div>
                                <div class="col-span-10 pr-4">
                                    {{ $encuesta->descripcion }}
                                </div>
                                <div class="col-span-2 font-bold">
                                    Estad
                                </div>
                                <div class="col-span-10 pr-4">
                                    {{ ucfirst($encuesta->estado()['nombre']) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col">
                        @livewire('capacitaciones.capacitacions.show.invitaciones', ['capacitacion' => $capacitacion], key($capacitacion->id . microtime(true)))
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
