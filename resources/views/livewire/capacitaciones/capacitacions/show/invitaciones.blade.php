<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="subtitulo-show">
        Listado de Invitados
    </div>
    <div class="grid grid-cols-10 gap-4 py-2">
        @can('Capacitaciones/Capacitaciones/Editar')
        <div class="col-span-8">
            <select wire:model.live="user_id" class="input-full">
                <option>Seleccione un usuario de la lista</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{$usuario->id}}">{{$usuario->legajo}} - {{$usuario->realname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-2">
            <button class="boton-celeste text-sm" wire:click="agregar()">Agregar</button>
        </div>
        @endcan
    </div>
    <table class="table-index">
        <thead>
            <th class="th-index">Nombre</th>
            <th class="th-index">Legajo</th>
            <th class="th-index">Presente</th>
            <th class="th-index">Editar</th>
        </thead>
        <tbody>
            @foreach ($capacitacion->invitaciones as $invitacion)
                <tr class="hover:bg-gray-100">
                    <td class="td-index">{{$invitacion->usuario->realname}}</td>
                    <td class="td-index">{{$invitacion->usuario->legajo}}</td>
                    <td class="td-index">
                        <button wire:click="presente({{$invitacion->id}})" class="
                        {{$invitacion->presente 
                        ? 'bg-green-200 hover:bg-orange-400' 
                        : 'bg-orange-200 hover:bg-green-400'}}
                        text-xs rounded-lg px-2
                        ">
                            {{$invitacion->presente ? 'Presente' : 'Ausente'}}
                        </button>
                    </td>
                    <td class="td-index">
                        @can('Capacitaciones/Capacitaciones/Editar')
                        <span class="link-azul cursor-pointer" wire:click="quitar({{$invitacion->id}})">
                            Quitar
                        </span>
                        @endcan
                    </td>
                </tr> 
            @endforeach
        </tbody>
    </table>
</div>
