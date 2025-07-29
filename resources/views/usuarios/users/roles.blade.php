<x-app-layout>
    <div class="w-full max-w-7xl mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <form action="{{ route('usuarios.users.roles', $user) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-10 py-4">
                        <div class="col-span-8 text-2xl mb-2 pl-3">
                            Listado Roles para el usuario: {{ $user->realname }}
                        </div>
                        <div class="col-span-2 text-center">
                            <button type="submit" class="block w-full bg-blue-400 text-white rounded-lg px-5 py-1 mt-1">Guardar</a>
                        </div>
                    </div>
                    <table class="items-center w-full border-collapse bg-white shadow-lg rounded">
                        <thead>
                            <tr>
                                <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    ID
                                </th>
                                <th class="px-6 al ign-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Nombre
                                </th>
                                <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $sistema = '';
                            @endphp
                            @foreach ($roles as $role)
                            @php
                                $actual = explode('/', $role->name)[0];
                            @endphp
                            @if ($actual != $sistema)
                                @php
                                    $sistema = $actual;
                                @endphp
                                <tr>
                                    <th colspan="4" class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap py-2 text-left">
                                        {{$sistema}}
                                    </th>
                                </tr>
                            @endif
                            <tr class="hover:bg-gray-300">
                                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap py-2 text-left">
                                    {{$role->id}}
                                </th>
                                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap py-1 ">
                                    {{$role->name}}
                                </td>
                                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap py-1">
                                    <input type="checkbox" name="roles[{{$role->name}}]"  @checked($user->hasRole($role->name)) >
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
