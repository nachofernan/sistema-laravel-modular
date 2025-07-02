<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    @foreach ($areas as $area)
    <tr class="hover:bg-gray-300">
        <td class="td-index">
            {{$area->id}}
        </td>
        <td class="td-index ">
            {{ $nivel }}  
            {{$area->nombre}}
        </td>
        <td class="td-index">
            {{count($area->users)}}
        </td>
        <td class="td-index">
            @can('Usuarios/Areas/Editar')
            <a href="{{ route('usuarios.areas.edit', $area) }}" class="link-azul">Editar Área</a>
            @endcan
        </td>
    </tr>
    @if ($area->hijos)
    @livewire('usuarios.areas.foreach-table-tr', ['areas' => $area->hijos, 'nivel' => ($nivel . ' — ')])
    @endif
    @endforeach
</div>
