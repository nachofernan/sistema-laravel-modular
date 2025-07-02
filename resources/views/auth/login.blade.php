<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-application-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="legajo" value="{{ __('Legajo') }}" />
                <x-input id="legajo" class="block mt-1 w-full" type="number" name="legajo" :value="old('legajo')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4 relative">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />
                <div class="absolute inset-y-0 right-0 mr-4 mt-6 flex items-center cursor-pointer">
                    <i class="far fa-eye-slash" id="togglePassword" style="color: #9CA3AF;"></i>
                </div>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{route('password.forgot')}}" class="text-sm hover:underline text-gray-600">Olvidé la contraseña</a>
                <x-button class="ml-4">
                    {{ __('Ingresar') }}
                </x-button>
            </div>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                const togglePassword = document.querySelector('#togglePassword');
                const password = document.querySelector('#password');
            
                togglePassword.addEventListener('click', function (e) {
                    // Toggle the type attribute
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    
                    // Toggle the eye / eye slash icon
                    this.classList.toggle('fa-eye-slash');
                    this.classList.toggle('fa-eye');
                });
            });
            </script>
    </x-authentication-card>
</x-guest-layout>
