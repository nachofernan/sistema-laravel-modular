<div>
    @foreach ($areas as $area)
        @php
            $esUltimo = $loop->last;
            $tieneHijos = $area->hijos->isNotEmpty();
        @endphp
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-400">
                {{ $area->id }}
            </td>
            <td class="pr-6 whitespace-nowrap">
                <div class="flex items-stretch">
                    {{-- Líneas guía de los niveles ancestros --}}
                    @foreach ($ancestorsLast as $ancestorLast)
                        <div class="flex-none w-6 relative">
                            @unless ($ancestorLast)
                                <span class="absolute inset-y-0 left-1/2 w-px bg-gray-200"></span>
                            @endunless
                        </div>
                    @endforeach

                    {{-- Conector del nodo actual (├ o └) --}}
                    @if ($depth >= 1)
                        <div class="flex-none w-6 relative">
                            {{-- tramo superior: baja desde arriba hasta el centro (conecta con el padre) --}}
                            <span class="absolute top-0 h-1/2 left-1/2 w-px bg-gray-200"></span>
                            {{-- tramo inferior: sigue hacia abajo solo si no es el último hermano --}}
                            @unless ($esUltimo)
                                <span class="absolute bottom-0 h-1/2 left-1/2 w-px bg-gray-200"></span>
                            @endunless
                            {{-- tramo horizontal: del centro hacia el ícono --}}
                            <span class="absolute top-1/2 left-1/2 right-0 h-px bg-gray-200"></span>
                        </div>
                    @endif

                    {{-- Ícono + contenido --}}
                    <div class="flex items-start gap-2 py-3 pl-1">
                        <span class="flex-none mt-0.5">
                            @if ($tieneHijos)
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </span>
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $area->nombre }}
                                @if ($area->tipo)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $area->tipo->nombre }}
                                    </span>
                                @endif
                                @unless ($area->activa)
                                    <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-600">
                                        Inactiva
                                    </span>
                                @endunless
                            </div>
                            @if ($area->responsable)
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Responsable: {{ $area->responsable->nombreCompleto }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-3 whitespace-nowrap text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ count($area->users) }}
                    {{ count($area->users) == 1 ? 'persona' : 'personas' }}
                </span>
            </td>
            <td class="px-6 py-3 whitespace-nowrap text-center">
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

        @if ($tieneHijos)
            @livewire('usuarios.areas.foreach-table-tr', [
                'areas' => $area->hijos,
                'depth' => $depth + 1,
                'ancestorsLast' => array_merge($ancestorsLast, $depth >= 1 ? [$esUltimo] : []),
            ])
        @endif
    @endforeach
</div>
