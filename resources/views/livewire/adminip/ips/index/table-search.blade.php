<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="grid grid-cols-10 gap-3 pb-2">
        <div class="col-span-2">
            <select wire:model.change="input_a" class="input-full">
                <option value="todos">Todos</option>
                @foreach ($bloque_a as $ba)
                    <option value="{{$ba->bloque_a}}">{{$ba->bloque_a}}</option>
                @endforeach
            </select>
        </div>
        @if ($input_a != 'todos')
            <div class="col-span-2">
                <select wire:model.change="input_b" class="input-full">
                    <option value="todos">Todos</option>
                    @foreach ($bloque_b as $bb)
                        <option value="{{$bb->bloque_b}}">{{$bb->bloque_b}}</option>
                    @endforeach
                </select>
            </div>
            @if ($input_b != 'todos')
                <div class="col-span-2">
                    <select wire:model.change="input_c" class="input-full">
                        <option value="todos">Todos</option>
                        @foreach ($bloque_c as $bc)
                            <option value="{{$bc->bloque_c}}">{{$bc->bloque_c}}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        @endif
        <div class="col-start-7 col-span-4">
            <input type="text" wire:model.live="search" class="input-full" placeholder="Buscar por nombres">
        </div>
    </div>
    <table class="table-index">
        <thead>
            <tr>
                <th class="th-index">
                    IP
                </th>
                <th class="th-index">
                    Nombre
                </th>
                <th class="th-index">
                    Descripción
                </th>
                <th class="th-index">
                    
                </th>
                <th class="th-index">
                    
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($ips as $ip)    
            <tr class="hover:bg-gray-300">
                <td class="td-index">
                    {{$ip->ip}}
                </td>
                <td class="td-index">
                    {{$ip->nombre}}
                </td>
                <td class="td-index">
                    {{$ip->descripcion}}
                </td>
                <td class="td-index">
                    @can('AdminIP/IPS/Editar')
                    @livewire('adminip.ips.index.check', ['ip' => $ip], key($ip->id.microtime(true)))
                    @endcan
                </td>
                <td class="td-index">
                    @livewire('adminip.ips.index.editar', ['ip' => $ip], key($ip->id.microtime(true)))
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="px-4 py-2">
                    {{ $ips->links() }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
