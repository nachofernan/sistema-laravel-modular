<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-application-logo />
        </x-slot>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.send') }}">
            @csrf

            <div class="block">
                <x-label for="legajo" value="{{ __('Legajo') }}" />
                <x-input id="legajo" class="block mt-1 w-full" type="number" name="legajo" :value="old('legajo')" required autofocus autocomplete="legajo" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button onclick="this.disabled=true; this.innerText='Enviando...'; this.form.submit();" >
                    {{ __('Enviar Link de Recuperación') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
