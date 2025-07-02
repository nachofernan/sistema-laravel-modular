<div>
    {{-- The Master doesn't talk, he acts. --}}
    <button class="text-blue-700 hover:underline text-sm" wire:click="$set('open', true)"> 
        Editar
    </button>
    <x-dialog-modal wire:model="open"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="border-b py-2 grid grid-cols-2"> 
                <div class="font-bold col mt-1">
                    Editar Concurso
                </div>
                <div class="col text-right">
                    <button wire:click="guardar()" class="boton-celeste text-sm">Guardar</button>
                </div>
            </div>                 
                
                
        </x-slot> 
        <x-slot name="content">
            <div class="grid-datos-show">
                <div class="atributo-edit">
                    Nombre
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="nombre" value="{{ $nombre }}" class="input-full" placeholder="Nombre" required autocomplete="off">
                </div>
                <div class="atributo-edit">
                    Descripción
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="descripcion" value="{{ $descripcion }}" class="input-full" placeholder="Descripción" required autocomplete="off">
                </div>
                <div class="atributo-edit">
                    Numero de Legajo
                </div>
                <div class="valor-edit">
                    @livewire('concursos.concurso.create.legajo-input', ['numero_legajo' => $numero_legajo])
                    {{-- <input type="text" name="numero_legajo" value="{{ $numero_legajo }}" class="input-full" placeholder="Número de Legajo" required> --}} 
                </div>
                <div class="atributo-edit">
                    Link del Legajo
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="legajo" value="{{ $legajo }}" class="input-full" placeholder="Código del Legajo" required autocomplete="off">
                </div>
                @if($concurso->estado_id == 1)
                <div class="atributo-edit">
                    Fecha Cierre
                </div>
                <div class="valor-edit">
                    <input type="datetime-local" wire:model="fecha_cierre" value="{{ $fecha_cierre }}" class="input-full" placeholder="Fecha Cierre" required autocomplete="off" min="{{ now()->format('Y-m-d H:i') }}">
                </div>
                @endif
                <div class="atributo-edit">
                    Usuario Gestor
                </div>
                <div class="valor-edit">
                    <select wire:model="user_id" class="input-full">
                        <option value="">Sin Gestor</option>
                        @foreach(App\Models\User::permission('Concursos/Concursos/Crear')->get() as $user)
                        <option value="{{$user->id}}" @selected($user->id == $user_id)>{{$user->realname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="atributo-edit">
                    Sedes
                </div>
                <div class="valor-edit mt-1">
                    @foreach($sedes as $sede)
                    <div class="py-1">
                        <input 
                            type="checkbox" 
                            wire:model="sedeSeleccionadas" 
                            value="{{$sede->id}}" 
                            class="border-gray-400 rounded mx-2 mb-1"
                        > 
                        {{$sede->nombre}}
                    </div>
                    @endforeach
                </div>
            </div>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
        </div>
    </x-dialog-modal> 
</div>
