<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <button wire:click="$set('open', true)" class="boton-celeste">
        Editar
    </button>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            <div class="titulo-show text-left">
                Editar Ticket
            </div>
        </x-slot>
        <x-slot name="content">
            <div class="text-left">
                <form action="{{ route('tickets.tickets.update', $ticket) }}" method="post">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <div class="text-sm">
                        <div class="pb-2">
                            Categoria
                            <select name="categoria_id"
                                class="input-full">
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ $ticket->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pb-2">
                            Estado
                            <select name="estado_id"
                                class="input-full">
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}"
                                        {{ $ticket->estado_id == $estado->id ? 'selected' : '' }}>
                                        {{ $estado->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pb-2">
                            Encargado
                            <select name="user_encargado_id"
                                class="input-full">
                                <option value="">Sin Asignar</option>
                                @foreach ($users as $user)
                                @php
                                    if($user->legajo == '00000') {continue;}
                                @endphp
                                    <option value="{{ $user->id }}"
                                        {{ $ticket->user_encargado_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->realname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="w-full text-right">
                        <button type="submit" class="boton-celeste">Guardar</button>
                    </div>
                </form>
            </div>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
