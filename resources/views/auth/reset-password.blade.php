<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-application-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ request()->email }}">
        
            <div class="col-span-6 sm:col-span-4 pb-4 relative">
                <x-label for="password" value="{{ __('Nueva Contraseña') }}"/>
                <x-input id="password" type="password" class="mt-1 block w-full pr-10"
                    name="password" autocomplete="new-password" />
                <div class="absolute inset-y-0 right-0 mr-4 mt-2 flex items-center cursor-pointer">
                    <i class="far fa-eye-slash toggle-password" data-input="password" style="color: #9CA3AF;"></i>
                </div>
                @error('password')
                    <x-input-error for="password" />
                @enderror
            </div>
            
            <div class="col-span-6 sm:col-span-4 pb-4 relative">
                <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}"/>
                <x-input id="password_confirmation" type="password" class="mt-1 block w-full pr-10"
                    name="password_confirmation" autocomplete="new-password" />
                <div class="absolute inset-y-0 right-0 mr-4 mt-2 flex items-center cursor-pointer">
                    <i class="far fa-eye-slash toggle-password" data-input="password_confirmation" style="color: #9CA3AF;"></i>
                </div>
                @error('password_confirmation')
                    <x-input-error for="password_confirmation" />
                @enderror
            </div>                                        

            <div class="form-group grid grid-cols-6">
                <div class="col-span-4">
                    <p class="text-xs pr-20 pt-1">
                        La nueva contraseña debe tener al menos 8 caractéres, incluyendo una mayúscula, una minúscula y un número.
                    </p>
                </div>
                <div class="col-span-2 text-right">
                    <button type="submit" class="boton-celeste">
                        Actualizar Contraseña
                    </button>
                </div>
            </div>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                const togglePasswordIcons = document.querySelectorAll('.toggle-password');
            
                togglePasswordIcons.forEach(icon => {
                    icon.addEventListener('click', function (e) {
                        // Obtener el id del input relacionado
                        const inputId = this.getAttribute('data-input');
                        const passwordInput = document.querySelector(`#${inputId}`);
            
                        // Alternar el atributo 'type'
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);
            
                        // Alternar los iconos de ojo / ojo con barra
                        this.classList.toggle('fa-eye-slash');
                        this.classList.toggle('fa-eye');
                    });
                });
            });
            </script>
    </x-authentication-card>
</x-guest-layout>
