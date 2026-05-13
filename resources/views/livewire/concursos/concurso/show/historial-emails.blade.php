<div>
    <button
        class="inline-flex items-center px-3 py-1.5 bg-gray-700 hover:bg-gray-800 text-white text-sm rounded-md transition-colors"
        wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1">
            <path d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
            <path d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
        </svg>
        Correos
    </button>

    <x-dialog-modal wire:model="open" maxWidth="4xl">
        <x-slot name="title">
            <div class="border-b py-2">
                Historial de correos — {{ $concurso->nombre }} #{{ $concurso->numero }}
            </div>
        </x-slot>

        <x-slot name="content">

            {{-- Pendientes --}}
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-yellow-400"></span>
                    Pendientes de envío ({{ $pendientes->count() }})
                </h4>

                @if ($pendientes->isEmpty())
                    <p class="text-sm text-gray-400 italic">No hay correos pendientes.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 border-b">
                                    <th class="pb-2 pr-4 font-medium">Destinatario</th>
                                    <th class="pb-2 pr-4 font-medium">Tipo</th>
                                    <th class="pb-2 pr-4 font-medium">Programado para</th>
                                    <th class="pb-2 font-medium">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($pendientes as $job)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 pr-4 text-gray-800">{{ $job->destinatario ?? '—' }}</td>
                                        <td class="py-2 pr-4 text-gray-600">{{ $job->job_type }}</td>
                                        <td class="py-2 pr-4 text-gray-600">{{ \Carbon\Carbon::parse($job->scheduled_for)->format('d-m-Y H:i') }}</td>
                                        <td class="py-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                pendiente
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Enviados --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-400"></span>
                    Historial de enviados ({{ $enviados->count() }})
                </h4>

                @if ($enviados->isEmpty())
                    <p class="text-sm text-gray-400 italic">No hay correos registrados.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 border-b">
                                    <th class="pb-2 pr-4 font-medium">Destinatario</th>
                                    <th class="pb-2 pr-4 font-medium">Descripción</th>
                                    <th class="pb-2 pr-4 font-medium">Fecha</th>
                                    <th class="pb-2 font-medium">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($enviados as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 pr-4 text-gray-800">
                                            {{ $log->destinatario }}
                                            @if ($log->destinatario_original)
                                                <span class="text-xs text-gray-400 block">orig: {{ $log->destinatario_original }}</span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4 text-gray-600">{{ $log->descripcion }}</td>
                                        <td class="py-2 pr-4 text-gray-600">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i') }}</td>
                                        <td class="py-2">
                                            @php
                                                $badges = [
                                                    'exitoso'  => 'bg-green-100 text-green-800',
                                                    'fallido'  => 'bg-red-100 text-red-800',
                                                    'bloqueado'=> 'bg-orange-100 text-orange-800',
                                                    'pendiente'=> 'bg-yellow-100 text-yellow-800',
                                                ];
                                                $clase = $badges[$log->estado] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $clase }}">
                                                {{ $log->estado }}
                                            </span>
                                            @if ($log->estado === 'fallido' && $log->error)
                                                <span class="text-xs text-red-500 block mt-0.5" title="{{ $log->error }}">
                                                    {{ \Illuminate\Support\Str::limit($log->error, 40) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </x-slot>

        <x-slot name="footer">
            <button wire:click="$set('open', false)" class="boton-celeste text-white bg-gray-600 hover:bg-gray-800">
                Cerrar
            </button>
        </x-slot>
    </x-dialog-modal>
</div>
