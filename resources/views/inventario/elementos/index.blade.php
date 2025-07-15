<x-app-layout>
    <x-page-header title="Inventario de Elementos">
        <x-slot:actions>
            @can('Inventario/Elementos/Crear')
                <a href="{{ route('inventario.elementos.create') }}" 
                   class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    + Nuevo Elemento
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>
    
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                @livewire('inventario.elementos.index.search')
            </div>
        </div>
    </div>
</x-app-layout>