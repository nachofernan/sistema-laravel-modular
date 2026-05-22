<x-app-layout>
    <x-page-header title="Usuarios Externos (Portal Proveedores)">
    </x-page-header>

    <div class="mb-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            @livewire('proveedores.externos.index', [], key(microtime(true)))
        </div>
    </div>
</x-app-layout>
