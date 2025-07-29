<x-app-layout>
    <x-page-header title="Usuarios con Elementos">
        <x-slot:actions>
            <!-- Los usuarios se gestionan desde el módulo de usuarios, no se crean aquí -->
        </x-slot:actions>
    </x-page-header>
    
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                @livewire('inventario.users.index.search')
            </div>
        </div>
    </div>
</x-app-layout>