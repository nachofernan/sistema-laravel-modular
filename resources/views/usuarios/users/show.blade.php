<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8">
                    <div class="titulo-show">
                        {{ $user->realname }}
                    </div>
                    <div class="text-xs">
                        {{ $user->name }}
                        - {{ $user->legajo }}
                    </div>
                </div>
                <div class="col-span-4 text-right pt-3 text-sm">
                    @can('Usuarios/Usuarios/Editar')
                    <a href="{{ route('usuarios.users.edit', $user) }}" class="boton-celeste">Editar</a>
                    @endcan
                </div>
            </div>
            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="grid grid-cols-2 gap-5">
                    <div class="col">
                        <div class="subtitulo-show">
                            Datos de usuario y empresariales
                        </div>
                        <div class="grid-datos-show">
                            <div class="atributo-show">
                                Cargado
                            </div>
                            <div class="valor-show">
                                {{ Carbon\Carbon::create($user->created_at)->format('d-m-Y') }}
                            </div>
                            <div class="atributo-show">
                                Último Acceso
                            </div>
                            <div class="valor-show">
                                {{ $user->last_login ? \Carbon\Carbon::create($user->last_login)->format('d-m-Y H:i:s') : 'Nunca' }}
                            </div>
                            <div class="atributo-show">
                                Visibilidad en buscador
                            </div>
                            <div class="valor-show">
                                @if ($user->visible)
                                    Si
                                @else
                                    No
                                @endif
                            </div>
                            <div class="atributo-show">
                                Nombre de usuario
                            </div>
                            <div class="valor-show">
                                {{ $user->name }}
                            </div>

                            <div class="atributo-show">
                                Nombre Real
                            </div>
                            <div class="valor-show">
                                {{ $user->realname }}
                            </div>
                            <div class="atributo-show">
                                Correo Electrónico
                            </div>
                            <div class="valor-show">
                                {{ $user->email }}
                            </div>
                            <div class="atributo-show">
                                Legajo
                            </div>
                            <div class="valor-show">
                                {{ $user->legajo }}
                            </div>
                            <div class="atributo-show">
                                Interno
                            </div>
                            <div class="valor-show">
                                {{ $user->interno }}
                            </div>

                            <div class="atributo-show">
                                Sede
                            </div>
                            <div class="valor-show">
                                {{ $user->sede->nombre ?? '' }}
                            </div>
                            <div class="atributo-show">
                                Área
                            </div>
                            <div class="valor-show">
                                {{ $user->area->nombre ?? '' }}
                            </div>
                        </div>
                        <div class="subtitulo-show pt-4">
                            Últimos 10 movimientos
                        </div>
                        <table class="table table-responsive w-full">
                            @foreach ($user->logs->reverse()->take(10) as $log)
                            <tr>
                                <td class="text-xs text-right text-gray-500 w-1/4 py-1 px-3">
                                    {{Carbon\Carbon::create($log->created_at)->format('d-m-Y H:i')}}
                                </td>
                                <td class="text-sm w-2/4 py-1 px-3">
                                    {{$log->evento}}
                                </td>
                                <td class="text-xs text-right text-gray-500 w-1/4 py-1 px-3">
                                    {{$log->ip_address}}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="col">
                        <div class="subtitulo-show">
                            Accesos y Roles
                        </div>
                        <div class="pb-4">
                            @foreach ($user->getRoleNames()->sort() as $role)
                                <div class="pl-5 py-1 text-sm">
                                    {{ $role }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
