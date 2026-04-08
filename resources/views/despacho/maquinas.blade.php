<x-app-layout>
    <div class="max-w-screen-5xl mx-auto mt-6 px-4 pb-10">
        {{-- En pantallas grandes: dos columnas lado a lado.
             En laptops/chicas: apila verticalmente. --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

            <div class="bg-white rounded shadow">
                @livewire('despacho.maquinas-index')
            </div>

            <div class="bg-white rounded shadow">
                @livewire('despacho.registradores-index')
            </div>

        </div>
    </div>
</x-app-layout>