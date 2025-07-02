<div>
    {{-- Stop trying to control. --}}
    @foreach ($areas as $area)
    <option value="{{$area->id}}"
    @if ($area->id == $area_id)
        @if($disabled)
        disabled
        @else
        selected
        @endif
        class="font-bold"
    @endif
    >
        {{ $nivel }} - 
        {{$area->nombre}}
    </option>
    @if ($area->hijos)
    @livewire('usuarios.areas.foreach-select', ['areas' => $area->hijos, 'area_id' => $area_id, 'disabled' => $disabled, 'nivel' => ($nivel . ' | ')])
    @endif
    @endforeach
</div>
