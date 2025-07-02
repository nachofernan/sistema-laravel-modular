<div>
    {{-- Do your work, then step back. --}}
    <button wire:click="$set('open', true)" class="block w-full text-sm py-2 boton-celeste">
        Nueva IP
    </button>
    <x-dialog-modal wire:model="open">
        <x-slot name="title">Nueva IP</x-slot>
        <x-slot name="content">
            <form wire:submit="guardar()">
            <div class="grid-datos-show">
                <div class="atributo-edit">
                    Nombre
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="nombre" class="input-full">
                    <div>@error('nombre') {{ $message }} @enderror</div>
                </div>
                <div class="atributo-edit">
                    IP
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="ip" class="input-full">
                    <div>@error('ip') {{ $message }} @enderror</div>
                </div>
                <div class="atributo-edit">
                    MAC
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="mac" class="input-full">
                </div>
                <div class="atributo-edit">
                    Descripción
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="descripcion" class="input-full">
                </div>
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
                <div class="atributo-edit">
                    Usuario Asignado
                </div>
                <div class="valor-edit">
                    <select wire:model="user_id" class="input-full">
                        <option>Sin usuario asignado</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->realname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="atributo-edit">
                </div>
                <div class="valor-edit text-right">
                    <button tipe="submit" class="boton-celeste">Crear Nuevo</button>
                </div>
            </div>
            </form>
        </x-slot>
        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>
