
        <!-- Account Management -->
        <div class="block px-4 py-2 text-xs text-gray-400">
            {{ __('Accesos') }}
        </div>

        {{-- @foreach (Auth::user()->getSistemasAcceso() as $sistema)
        @php
            // Convertir el nombre del sistema a minúsculas para la comparación y las rutas
            $lowerSistema = strtolower($sistema);
        @endphp

            @if ($lowerSistema == 'plataforma' || $lowerSistema == 'intranet')
                <x-dropdown-link href="{{ route('home') }}" class="{{ $module == 'guest' ? 'font-bold' : '' }}">
                    {{ $sistema }}
                </x-dropdown-link>
            @else
                <x-dropdown-link href="{{ route($lowerSistema) }}" class="{{ $lowerSistema == $module ? 'font-bold' : '' }}">
                    {{ $sistema }}
                </x-dropdown-link>
            @endif
        @endforeach  --}}
        @foreach (App\Models\Usuarios\Modulo::where('estado', '!=', 'inactivo')->orderBy('nombre')->get() as $modulo)
        @php
            if(!Auth::user()->hasRole($modulo->nombre."/Acceso")) {continue;}
            $moduloLower = strtolower($modulo->nombre);
        @endphp

            @if ($moduloLower == 'plataforma' || $moduloLower == 'intranet')
                <x-dropdown-link href="{{ route('home') }}" class="{{ $module == 'guest' ? 'font-bold' : '' }}">
                    {{ $modulo->nombre }}
                </x-dropdown-link>
            @else
                @if (Route::has($moduloLower))
                    <x-dropdown-link href="{{ route($moduloLower) }}" class="{{ $moduloLower == $module ? 'font-bold' : '' }}">
                        {{ $modulo->nombre }}
                        @if ($modulo->estado == 'mantenimiento')
                        <span class="ml-1 font-bold bg-red-800 text-white text-xs rounded px-2 py-0" title="Mantenimiento">!</span>
                        @endif
                    </x-dropdown-link>
                @endif
            @endif
        @endforeach 

        <div class="border-t border-gray-200"></div>

		<div class="block px-4 py-2 text-xs text-gray-400">
            {{ Auth::user()->realname }}
        </div>


        <!-- Actualizar Contraseña -->
        <x-dropdown-link href="{{ route('usuarios.change.password') }}">
            {{ __('Actualizar Contraseña') }}
        </x-dropdown-link>

        <div class="border-t border-gray-200"></div>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}" x-data>
            @csrf

            <x-dropdown-link href="{{ route('logout') }}"
                    @click.prevent="$root.submit();">
                {{ __('Salir') }}
            </x-dropdown-link>
        </form>
