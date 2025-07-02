<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8">
                    <div class="titulo-show">
                        {{ $encuesta->nombre }}
                    </div>
                    <div class="text-xs">
                        <a href="{{ route('capacitaciones.capacitacions.show', $encuesta->capacitacion) }}" class="link-azul">Volver a la capacitación</a>
                    </div>
                </div>
                <div class="col-span-4 mt-1 text-sm">
                    @can('Capacitaciones/Encuestas/Editar')
                        @livewire('capacitaciones.encuesta.show.editar-encuesta', ['encuesta' => $encuesta], key($encuesta->id.microtime(true)))
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
                                {{ $encuesta->nombre }}
                            </div>
                            <div class="atributo-show">
                                Capacitación
                            </div>
                            <div class="valor-show">
                                <a href="{{ route('capacitaciones.capacitacions.show', $encuesta->capacitacion) }}" class="link-azul">{{$encuesta->capacitacion->nombre}}</a>
                            </div>
                            <div class="atributo-show">
                                Estado
                            </div>
                            <div class="valor-show">
                                {{ ucfirst($encuesta->estado()['nombre']) }}
                            </div>
                            <div class="atributo-show">
                                Descripción
                            </div>
                            <div class="valor-show">
                                {!! nl2br($encuesta->descripcion) !!}
                            </div>
                        </div>
                        <div class="subtitulo-show pt-2 mt-2 border-t">
                            Usuarios que pueden responder
                        </div>
                        <div class="grid grid-cols-6 gap-4 text-sm">
                            @foreach ($encuesta->capacitacion->invitaciones->filter(function($i){return $i->presente;}) as $invitacionPresente)
                            <div class="col text-right">
                                {{ $invitacionPresente->usuario->legajo }}
                            </div>
                            <div class="col-span-3">
                                {{ $invitacionPresente->usuario->realname }}
                            </div>
                            <div class="col-span-2 text-right">
                                @if ($encuesta->estado != 0)
                                    @if ($encuesta->respondida_por($invitacionPresente->usuario->id)->isEmpty())
                                        <button class="bg-red-200 text-xs rounded-lg px-2">Sin responder</button>
                                    @else
                                        <button class="bg-green-200 text-xs rounded-lg px-2">Respondida</button>    
                                    @endif
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col">
                        <div class="subtitulo-show grid grid-cols-4">
                            <div class="col-span-3">
                                Preguntas y Respuestas
                            </div>
                            <div class="col text-sm">
                                @if ($encuesta->estado == 0)
                                @can('Capacitaciones/Encuestas/Editar')
                                @livewire('capacitaciones.encuesta.show.agregar-pregunta', ['encuesta' => $encuesta], key($encuesta->id.microtime(true)))
                                @endcan
                                @endif
                            </div>
                        </div>
                        @foreach ($encuesta->preguntas as $key => $pregunta)
                        <div class="grid-datos-show py-2 border-b">
                            <div class="col-span-4">
                                {{$key+1}} - {{$pregunta->pregunta}}
                            </div>
                            <div class="col-span-1">
                                @if ($encuesta->estado == 0)
                                @can('Capacitaciones/Encuestas/Editar')
                                @livewire('capacitaciones.encuesta.show.editar-pregunta', ['pregunta' => $pregunta], key($pregunta->id.microtime(true)))
                                @endcan
                                @endif
                            </div>
                            <div class="col-span-1">
                                @if ($encuesta->estado == 0)
                                @can('Capacitaciones/Encuestas/Editar')
                                @livewire('capacitaciones.encuesta.show.eliminar-pregunta', ['pregunta' => $pregunta], key($pregunta->id.microtime(true)))
                                @endcan
                                @endif
                            </div>
                            <div class="col-span-6">
                                @if ($pregunta->con_opciones)
                                    @foreach ($pregunta->opciones as $opcion)
                                    <div class="grid grid-cols-6 gap-3 px-10 hover:bg-gray-100 py-1">
                                        <div class="col-span-4">
                                            {{$opcion->opcion}}
                                        </div>
                                        <div class="col-span-2 text-right">
                                            @if ($encuesta->estado == 0)
                                            @can('Capacitaciones/Encuestas/Editar')
                                            <form action="{{route('capacitaciones.opcions.destroy', $opcion)}}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="link-azul text-red-600 text-xs">Eliminar</button>
                                            </form>
                                            @endcan
                                            @else
                                            Elegida:
                                            {{\App\Models\Capacitaciones\Respuesta::where('opcion_id', $opcion->id)->count()}}
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="pt-1 px-10">
                                        @can('Capacitaciones/Encuestas/Editar')
                                        <form action="{{route('capacitaciones.opcions.store')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="pregunta_id" value="{{$pregunta->id}}">
                                            <div class="grid grid-cols-6 gap-3">
                                                <div class="col-span-4">
                                                    <input type="text" class="input-full text-xs" name="opcion">
                                                </div>
                                                <div class="col-span-2">
                                                    <button type="submit" class="boton-celeste w-full text-xs">Agregar Opción</button>
                                                </div>
                                            </div>
                                        </form>
                                        @endcan
                                    </div>
                                @else
                                    @if ($encuesta->estado == 0)
                                        <i class="px-10">Esta pregunta es de texto libre</i>
                                    @else
                                        @foreach(\App\Models\Capacitaciones\Respuesta::where('pregunta_id', $pregunta->id)->get() as $respuesta)
                                        <div class="px-10 py-2 border-l-lg hover:bg-gray-100">{{$respuesta->respuesta}}</div>
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>