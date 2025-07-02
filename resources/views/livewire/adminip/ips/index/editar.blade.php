<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <button wire:click="$set('open', true)" class="link-azul">
        Ver Datos
    </button>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Actualizar IP
        </x-slot>
        <x-slot name="content">
            @can('AdminIP/IPS/Editar')
            <form wire:submit="guardar()">
            @endcan
            <div class="grid-datos-show">
                <div class="atributo-edit">
                    Nombre
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="nombre" class="input-full" @disabled(!Auth::user()->can('AdminIP/IPS/Editar'))>
                    <div>@error('nombre') {{ $message }} @enderror</div>
                </div>
                <div class="atributo-edit">
                    IP
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="ip" class="input-full" @disabled(!Auth::user()->can('AdminIP/IPS/Editar'))>
                    <div>@error('ip') {{ $message }} @enderror</div>
                </div>
                <div class="atributo-edit">
                    MAC
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="mac" class="input-full" @disabled(!Auth::user()->can('AdminIP/IPS/Editar'))>
                </div>
                <div class="atributo-edit">
                    Descripción
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="descripcion" class="input-full" @disabled(!Auth::user()->can('AdminIP/IPS/Editar'))>
                </div>
                @can('AdminIP/IPS/Editar')
                <div class="atributo-edit">
                    SSH_user
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="user" class="input-full">
                </div>
                <div class="atributo-edit">
                    SSH_password
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="password" class="input-full">
                </div>
                @endcan
                <div class="atributo-edit">
                    Usuario Asignado
                </div>
                <div class="valor-edit">
                    <select wire:model="user_id" class="input-full" @disabled(!Auth::user()->can('AdminIP/IPS/Editar'))>
                        <option>Sin usuario asignado</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected($user->id == $ip_db->user_id)>{{ $user->realname }}</option>
                        @endforeach
                    </select>
                </div>
                @can('AdminIP/IPS/Editar')
                <div class="atributo-edit">
                </div>
                <div class="valor-edit text-right">
                    <button tipe="submit" class="boton-celeste">Editar</button>
                </div>
                @endcan
            </div>
            @can('AdminIP/IPS/Editar')
            </form>
            @endcan
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
