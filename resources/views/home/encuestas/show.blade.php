<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-12">
                    <div class="titulo-show">
                        {{ $encuesta->nombre }}
                    </div>
                    <div class="text-xs">
                        <a href="{{ route('home.capacitacions.show', $encuesta->capacitacion) }}" class="link-azul">Volver a la capacitación</a>
                    </div>
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
                                {{ $encuesta->nombre }}
                            </div>
                            <div class="atributo-show">
                                Capacitación
                            </div>
                            <div class="valor-show">
                                <a href="{{ route('home.capacitacions.show', $encuesta->capacitacion) }}" class="link-azul">{{$encuesta->capacitacion->nombre}}</a>
                            </div>
                            <div class="atributo-show">
                                Descripción
                            </div>
                            <div class="valor-show">
                                {!! nl2br($encuesta->descripcion) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="subtitulo-show">
                            Preguntas y Respuestas
                        </div>
                        <form action="{{route('home.encuestas.store')}}" method="POST">
                            @csrf
                            @foreach ($encuesta->preguntas as $key => $pregunta)
                            <div class="grid-datos-show py-2 border-b">
                                <div class="col-span-6">
                                    {{$key+1}} - {{$pregunta->pregunta}}
                                </div>
                                <div class="col-span-6">
                                    @if ($pregunta->con_opciones)
                                    <select name="{{$pregunta->id}}" class="input-full" required>
                                        @foreach ($pregunta->opciones as $opcion)
                                        <option value="{{$opcion->id}}">{{$opcion->opcion}}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <textarea name="{{$pregunta->id}}" rows="4" class="input-full" required></textarea>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            <button type="submit" class="boton-celeste mt-2">Enviar Encuesta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>