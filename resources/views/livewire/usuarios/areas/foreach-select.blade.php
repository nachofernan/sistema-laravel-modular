<div>
    @foreach ($areas as $area)
        @php
            $esExcluido = $dentroExcluido || ($excludeId !== null && $area->id == $excludeId);
        @endphp
        <option value="{{ $area->id }}"
            @selected(! $esExcluido && $area->id == $selected)
            @disabled($esExcluido)
        >
            {{ $nivel }}{{ $area->nombre }}
        </option>
        @if ($area->hijos->isNotEmpty())
            @livewire('usuarios.areas.foreach-select', [
                'areas' => $area->hijos,
                'selected' => $selected,
                'excludeId' => $excludeId,
                'dentroExcluido' => $esExcluido,
                'nivel' => ($nivel . ' — '),
            ])
        @endif
    @endforeach
</div>
