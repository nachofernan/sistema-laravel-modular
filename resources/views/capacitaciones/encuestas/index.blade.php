<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 gap-4 py-2">
                    <div class="col-span-4 titulo-index">
                        Listado de Encuestas
                    </div>
                    <div class="col-span-4">
                        <input wire:model.live="search" type="text" placeholder="Buscar Capacitación"
                            class="input-full">
                    </div>
                    <div class="col-span-2 text-center">
                        @can('Capacitaciones/Capacitaciones/Crear')
                            <a href="{{ route('capacitaciones.capacitacions.create') }}" class="block w-full boton-celeste">Nueva Capacitación</a>
                        @endcan
                    </div>
                </div>
            Encuestas
            {!! link_to_route('capacitaciones.encuestas.create', 'Nueva Encuesta', null, ['class' => 'float-right rounded bg-blue-600 text-gray-100 text-sm py-1 px-5 mt-1']) !!}
        </h1>
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 px-5">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Personal
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Creación
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Duplicar
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($encuestas->filter(function ($item){ return $item->estado == 0 || $item->estado == 1;}) as $encuesta)
                <tr>
                    <td scope="col" class="px-6 py-2">
                        {!! link_to_route('capacitaciones.encuestas.show', $encuesta->nombre, [$encuesta], ['class' => 'font-bold hover:underline hover:text-blue-600']) !!}
                    </td>
                    <td scope="col" class="px-6">{{ count($encuesta->personals) }}</td>
                    <td scope="col" class="px-6">{{ Carbon\Carbon::create($encuesta->created_at)->format('d-m-Y') }}</td>
                    <td scope="col" class="px-6">{{ ucfirst($encuesta->estado()['nombre']) }}</td>
                    <td scope="col" class="px-6">
                        {!! link_to_route('capacitaciones.encuestas.duplicar', 'Duplicar', [$encuesta], ['class' => 'font-bold hover:underline hover:text-blue-600']) !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="border-b border-gray-300 my-3"></div>
        <div class="text-xl px-10 pb-3 border-b border-gray-300 font-bold">
            Encuestas Finalizadas
        </div>
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 px-5">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Personal
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Creación
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Duplicar
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($encuestas->filter(function ($item){ return $item->estado == 2;}) as $encuesta)
                <tr>
                    <td scope="col" class="px-6 py-2">
                        {!! link_to_route('capacitaciones.encuestas.show', $encuesta->nombre, [$encuesta], ['class' => 'font-bold hover:underline hover:text-blue-600']) !!}
                    </td>
                    <td scope="col" class="px-6">{{ count($encuesta->personals) }}</td>
                    <td scope="col" class="px-6">{{ Carbon\Carbon::create($encuesta->created_at)->format('d-m-Y') }}</td>
                    <td scope="col" class="px-6">{{ ucfirst($encuesta->estado()['nombre']) }}</td>
                    <td scope="col" class="px-6">
                        {!! link_to_route('capacitaciones.encuestas.duplicar', 'Duplicar', [$encuesta], ['class' => 'font-bold hover:underline hover:text-blue-600']) !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>