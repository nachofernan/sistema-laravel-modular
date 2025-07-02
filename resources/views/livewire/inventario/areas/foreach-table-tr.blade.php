<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    @foreach ($areas as $area)
    <tr class="hover:bg-gray-300">
        <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap py-1 text-left">
            {{$area->id}}
        </th>
        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap py-1 ">
            {{ $nivel }}  
            {{$area->nombre}}
        </td>
        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap py-1">
            {{count($area->users)}}
        </td>
        <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap py-1">
            <a href="{{ route('inventario.areas.edit', $area) }}">Editar Área</a>
        </td>
    </tr>
    @if ($area->hijos)
    @livewire('inventario.areas.foreach-table-tr', ['areas' => $area->hijos, 'nivel' => ($nivel . ' — ')])
    @endif
    @endforeach
</div>
