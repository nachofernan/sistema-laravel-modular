<div>
    @foreach ($areas as $area)
        <option value="{{ $area->id }}"
            @if ($area->id == $area_id)
                @if($disabled)
                    disabled
                @else
                    selected
                @endif
                class="font-medium bg-gray-100"
            @endif
        >
            {{ $nivel }}{{ $area->nombre }}
        </option>
        @if ($area->hijos)
            @livewire('usuarios.areas.foreach-select', [
                'areas' => $area->hijos, 
                'area_id' => $area_id, 
                'disabled' => $disabled, 
                'nivel' => ($nivel . ' — ')
            ])
        @endif
    @endforeach
</div>