<div>
    {{-- Be like water. --}}
    <tr>
        <td class="td-index">{{ $caracteristica->nombre }}</td>
        <td class="td-index text-center">
            <input type="checkbox" {{ $visible ? 'checked' : '' }}
            @can('Inventario/Inventario/Editar')
                wire:click="updateCaracteristica({{ $caracteristica->id }})"
            @endcan
            >
        </td>
    </tr>
</div>
