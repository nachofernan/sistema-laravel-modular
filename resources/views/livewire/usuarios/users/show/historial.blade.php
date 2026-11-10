<div>
    <div class="subtitulo-show">
        Historial de cambios
    </div>

    @if ($actividades->isEmpty())
        <div class="text-center py-4">
            <p class="text-sm text-gray-500">Sin cambios registrados</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden mt-3">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quién</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Detalle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($actividades as $actividad)
                        @php
                            $anterior = $actividad->properties['old'] ?? [];
                            $nuevo = $actividad->properties['attributes'] ?? [];
                        @endphp
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="px-3 py-2 text-xs text-gray-600 whitespace-nowrap">
                                {{ $actividad->created_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-900 whitespace-nowrap">
                                {{ $actividad->causer?->nombreCompleto ?? 'Sistema' }}
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-900 whitespace-nowrap">
                                {{ ucfirst($actividad->description) }}
                            </td>
                            <td class="px-3 py-2 text-xs text-gray-700">
                                @if ($actividad->event === 'roles')
                                    @foreach ($actividad->properties['agregados'] ?? [] as $rol)
                                        <div class="text-green-700">+ {{ $rol }}</div>
                                    @endforeach
                                    @foreach ($actividad->properties['quitados'] ?? [] as $rol)
                                        <div class="text-red-700">− {{ $rol }}</div>
                                    @endforeach
                                @elseif ($actividad->event === 'updated')
                                    @foreach ($nuevo as $campo => $valor)
                                        <div>
                                            <span class="font-medium">{{ $this::CAMPOS[$campo] ?? $campo }}:</span>
                                            <span class="text-gray-500 line-through">{{ $this->valorLegible($campo, $anterior[$campo] ?? null, $nombres) }}</span>
                                            →
                                            <span>{{ $this->valorLegible($campo, $valor, $nombres) }}</span>
                                        </div>
                                    @endforeach
                                @elseif ($actividad->event === 'created')
                                    @foreach ($nuevo as $campo => $valor)
                                        @continue($valor === null || $valor === '')
                                        <div>
                                            <span class="font-medium">{{ $this::CAMPOS[$campo] ?? $campo }}:</span>
                                            {{ $this->valorLegible($campo, $valor, $nombres) }}
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $actividades->links() }}
        </div>
    @endif
</div>
