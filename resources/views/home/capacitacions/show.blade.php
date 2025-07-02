<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class=" px-5 py-3 border-b pb-3">
                <div class="titulo-show">
                    {{ $capacitacion->nombre }}
                </div>
                <div class="text-xs">
                    {{ Carbon\Carbon::create($capacitacion->fecha)->format('d-m-Y') }}
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
                        @if ($invitacion->presente)    
                            <div class="subtitulo-show mt-2 pt-2 border-t">
                                Encuestas
                            </div>
                            <div class="grid grid-cols-6 gap-4 text-sm">
                                @if(session()->has('msg'))
                                    <div class="col-span-6 rounded-lg p-2 w-full text-center bg-green-200">
                                        Encuesta enviada con éxito!
                                    </div>
                                @endif
                                @foreach ($capacitacion->encuestas as $encuesta)
                                    @if ($encuesta->estado == 1 && $encuesta->respondida_por(Auth::user()->id)->isEmpty())
                                    <div class="col text-right">
                                        Nombre
                                    </div>
                                    <div class="col-span-3 font-bold">
                                        {{ $encuesta->nombre }}
                                    </div>
                                    <div class="col-span-2 text-center">
                                        <a href="{{route('home.encuestas.show', $encuesta)}}" class="link-azul">Responder</a>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <div class="subtitulo-show">
                            Documentos
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
                                    <a 
                                    href="{{Storage::disk('capacitaciones')->url($documento->file_storage)}}" 
                                    class="link-azul" target="_blank">
                                        Descargar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
