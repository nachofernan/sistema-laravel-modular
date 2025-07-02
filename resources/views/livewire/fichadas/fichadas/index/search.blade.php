<div>
    {{-- Do your work, then step back. --}}
    <div class="block w-full overflow-x-auto">
        <input type="date" wire:model.live="search" class="rounded py-2 px-3 text-gray-600 text-sm my-4" placeholder="Buscar por fecha">
        <table class="table-index">
            <thead>
                <tr>
                    <th class="th-index">
                        Personal
                    </th>
                    <th class="th-index">
                        Legajo
                    </th>
                    <th class="th-index">
                        Fecha
                    </th>
                    <th class="th-index">
                        Ingreso
                    </th>
                    <th class="th-index">
                        Egreso
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($fichadas as $grupoFichadas)
                    <tr class="hover:bg-gray-300">
                        <td class="td-index">
                            {{ $grupoFichadas->first()->usuario->realname ?? '-------- Usuario inexistente --------' }}
                        </td>
                        <td class="td-index">
                            {{ $grupoFichadas->first()->idEmpleado }}
                        </td>
                        <td class="td-index">
                            {{ $grupoFichadas->first()->fecha }}
                        </td>
                        <td class="td-index">
                            {{ $grupoFichadas->first()->hora }}
                        </td>
                        <td class="td-index">
                            {{ $grupoFichadas->last()->hora == $grupoFichadas->first()->hora ? '--------' : $grupoFichadas->last()->hora }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
