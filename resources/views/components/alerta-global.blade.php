@if (config('alerta.activo') && config('alerta.mensaje'))
    <div class="bg-red-600 text-white text-center text-sm sm:text-base font-semibold py-4 px-4 shadow">
        {{ config('alerta.mensaje') }}
    </div>
@endif
