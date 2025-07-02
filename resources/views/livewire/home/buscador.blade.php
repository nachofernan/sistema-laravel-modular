<div>
    {{-- Stop trying to control. --}}
    <div class="mx-auto grid grid-cols-12 gap-3 pb-2">
        <div class="col-span-8">
            <input wire:model.live="search" type="text" placeholder="Buscar usuario por nombre o interno" class="w-full rounded">
        </div>
        <div class="col-span-4">
            <select wire:model.live="sede_id" class="w-full rounded">
                <option value="">Todas las sedes</option>
                @foreach ($sedes as $sede)
                    <option value="{{$sede->id}}" {{$sede->id == $sede_id ? 'selected' : ''}}>{{$sede->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex flex-col">
            <div class="py-2 align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Interno
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre y Apellido
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Correo
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap w-1/4">
                                        <div class="items-center">
                                            <div class="h-full w-min-40 text-center font-bold text-xl text-gray-700">
                                                {{ $usuario->interno }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap w-1/2">
                                        <div class=" font-bold text-gray-900">
                                            <span class="text-lg">
                                                {{ $usuario->realname }} 
                                            </span>
                                            <small class="font-medium text-gray-500 text-sm hidden">
                                                 - {{$usuario->legajo}} 
                                            </small>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{$usuario->area->nombre ?? ''}}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-indigo-800">
                                            <a href="mailto:{{ $usuario->email }}">{{ $usuario->email }}</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- More people... -->
                        </tbody>
                    </table>
                </div>
                <div class="pt-5">
                    {{$usuarios->links()}}
                </div>
            </div>
        </div>
</div>
