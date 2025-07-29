<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    @foreach ($areas as $area)
    <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $area->id }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="text-sm font-medium text-gray-900">
                    {{ $nivel }} {{ $area->nombre }}
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ count($area->users) }} 
                {{ count($area->users) == 1 ? 'persona' : 'personas' }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            @can('Usuarios/Areas/Editar')
                <a href="{{ route('usuarios.areas.edit', $area) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
            @endcan
        </td>
    </tr>
    @if ($area->hijos)
        @livewire('usuarios.areas.foreach-table-tr', ['areas' => $area->hijos, 'nivel' => ($nivel . ' — ')])
    @endif
    @endforeach
</div>