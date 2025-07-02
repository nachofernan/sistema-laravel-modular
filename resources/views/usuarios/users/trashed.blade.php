<x-app-layout>
    <div class="w-full mb-12 xl:w-10/12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-8 titulo-index">
                        Listado Usuarios Eliminados
                    </div>
                </div>
                <table class="table-index">
                    <thead>
                        <tr>
                            <th class="th-index text-center">
                                ID
                            </th>
                            <th class="th-index">
                                Legajo
                            </th>
                            <th class="th-index">
                                Username
                            </th>
                            <th class="th-index">
                                Nombre
                            </th>
                            <th class="th-index">
                                Eliminado
                            </th>
                            <th class="th-index">
                                
                            </th>
                        </tr>
                    </thead>
            
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="hover:bg-gray-300">
                            <th class="td-index">
                                {{$user->id}}
                            </th>
                            <th class="td-index text-left">
                                {{$user->legajo}}
                            </th>
                            <td class="td-index ">
                                {{$user->name}}
                            </td>
                            <td class="td-index">
                                {{$user->realname}}
                            </td>
                            <td class="td-index">
                                {{ \Carbon\Carbon::create($user->deleted_at)->format('d-m-Y H:i:s')}}
                            </td>
                            <td class="td-index">
                                @livewire('usuarios.users.trashed.restore-modal', ['user' => $user], key($user->id.microtime(true)))
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
