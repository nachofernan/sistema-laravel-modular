<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8">
                    <div class="titulo-show">
                        #{{$ticket->codigo}} - ({{$ticket->categoria->nombre}}) - {{$ticket->user->realname}}
                    </div>
                </div>
                <div class="col-span-4 text-right text-sm">
                    @if (!$ticket->finalizado)
                        @can('Tickets/Tickets/Editar')
                        @livewire('tickets.tickets.show.edit', ['ticket' => $ticket], key($ticket->id))
                        @endcan
                    @endif
                </div>
            </div>

            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="grid grid-cols-6 gap-4">
                    <div class="col-span-2">
                        <div class="subtitulo-show">
                            Datos del Ticket
                        </div>
                        <div class="grid-datos-show">
                            <div class="atributo-show">
                                Código:
                            </div>
                            <div class="valor-show">
                                #{{$ticket->codigo}}
                            </div>
                            <div class="atributo-show">
                                Solicitado por:
                            </div>
                            <div class="valor-show">
                                {{$ticket->user->realname}}
                            </div>
                            <div class="atributo-show">
                                Categoria:
                            </div>
                            <div class="valor-show">
                                {{$ticket->categoria->nombre}}
                            </div>
                            <div class="atributo-show">
                                Documento:
                            </div>
                            <div class="valor-show">
                                @if ($ticket->documento)
                                    <a href="{{ route('home.tickets.documentos', $ticket) }}"
                                        class="boton-celeste text-xs" target="_blank">Descargar</a>
                                @else
                                    Sin documento
                                @endif
                            </div>
                            <div class="atributo-show">
                                Estado:
                            </div>
                            <div class="valor-show">
                                {{$ticket->estado->nombre}}
                            </div>
                            <div class="atributo-show">
                                Encargado:
                            </div>
                            <div class="valor-show">
                                {{$ticket->encargado->realname ?? 'Sin Asignar'}}
                            </div>
                            <div class="atributo-show">
                                Creación:
                            </div>
                            <div class="valor-show">
                                {{Carbon\Carbon::create($ticket->created_at)->format('d-m-Y H:i')}}
                            </div>
                            <div class="atributo-show">
                                Finalizado:
                            </div>
                            <div class="valor-show">
                                {{$ticket->finalizado ? Carbon\Carbon::create($ticket->finalizado)->format('d-m-Y H:i') : 'Todavía no'}}
                            </div>
                            <div class="atributo-show">
                                Notas:
                            </div>
                            <div class="valor-show">
                                {!!  nl2br($ticket->notas) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4">
                        <div class="subtitulo-show">
                            Mensajes
                        </div>
                        @can('Tickets/Tickets/Mensajes')
                            @if (!$ticket->finalizado)
                            <form action="{{route('tickets.mensajes.store')}}" method="POST">
                                @csrf
                                <div class="grid grid-cols-6 gap-4 text-sm">
                                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                                    <input type="text" name="mensaje" class="col-span-5 input-full" required placeholder="Ingrese su mensaje...">
                                    <button type="submit" class="col-span-1 boton-celeste">Enviar</button>
                                </div>
                            </form>
                            @endif
                        @endcan
                        @foreach ($ticket->mensajes->reverse() as $mensaje)
                            <div class="border-l-4 pl-2 py-2 text-sm my-2 pr-20 bg-gray-100 rounded">
                                <div class="text-xs">
                                    ID: {{$mensaje->id}} - <span class="font-bold">{{$mensaje->user->realname ?? 'Sistema'}}</span> - {{Carbon\Carbon::create($mensaje->created_at)->format('d-m-Y H:i')}}
                                    @can('Tickets/Tickets/Mensajes')
                                    @if ($mensaje->esNuevo())
                                        <span
                                            class="ml-4 font-bold bg-red-800 text-white text-xs rounded px-2 py-0">Nuevo</span>
                                        @php
                                            $mensaje->update(['leido' => 1]);
                                        @endphp
                                    @endif
                                    @endcan
                                </div>
                                {{$mensaje->mensaje}}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>