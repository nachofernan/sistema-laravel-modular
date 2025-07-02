<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('concursos.concursos.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Crear Nuevo Concurso
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('concursos.concursos.index') }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="subtitulo-show">
                                Datos del Concurso
                            </div>
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Nombre
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="input-full" placeholder="Nombre" required autocomplete="off">
                                    <div>@error('nombre') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Descripción
                                </div>
                                <div class="valor-edit">
                                    <textarea name="descripcion" class="input-full" placeholder="Descripción" rows="3" required>{{ old('descripcion') }}</textarea>
                                    <div>@error('descripcion') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Numero de Legajo
                                </div>
                                <div class="valor-edit">
                                    @livewire('concursos.concurso.create.legajo-input', ['numero_legajo' => old('numero_legajo')])
                                </div>
                                <div class="atributo-edit">
                                    Link al Legajo
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="legajo" value="{{ old('legajo') }}" class="input-full" placeholder="Link del Legajo" required autocomplete="off">
                                    <div>@error('legajo') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Fecha de Inicio
                                </div>
                                <div class="valor-edit">
                                    <input type="datetime-local" name="fecha_inicio" value="{{ old('fecha_inicio') ?? now()->format('Y-m-d H:i') }}" class="input-full" placeholder="Fecha Inicio" required autocomplete="off">
                                    <div>@error('fecha_inicio') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Fecha de Cierre
                                </div>
                                <div class="valor-edit">
                                    <input type="datetime-local" name="fecha_cierre" value="{{ old('fecha_cierre') ?? now()->addDays(14)->format('Y-m-d H:i') }}" class="input-full" placeholder="Fecha Cierre" required autocomplete="off" min="{{ now()->format('Y-m-d H:i') }}">
                                    <div>@error('fecha_cierre') {{ $message }} @enderror</div>
                                </div>
                                {{-- <div class="atributo-edit">
                                    Usuario Gestor
                                </div>
                                <div class="valor-edit">
                                    <select name="user_id" class="input-full">
                                        <option value="">Sin Gestor</option>
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}
                                            >
                                        {{$user->realname}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="atributo-edit">
                                    Sedes
                                </div>
                                <div class="valor-edit mt-1">
                                    @foreach($sedes as $sede)
                                    <div class="py-1">
                                        <input type="checkbox" name="sedes[]" value="{{$sede->id}}" class="border-gray-400 rounded mx-2 mb-1" 
                                            {{ in_array($sede->id, old('sedes', [])) ? 'checked' : '' }}
                                        >  
                                        {{$sede->nombre}}
                                    </div>
                                    @endforeach
                                    @error('sedes')
                                        <div class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>