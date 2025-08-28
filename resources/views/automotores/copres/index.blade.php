<x-app-layout>
    <x-page-header title="Gestión de COPRES">
        <x-slot:actions>
            @can('Automotores/COPRES/Crear')
                <a href="{{ route('automotores.copres.create') }}" 
                   class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm rounded-md transition-colors">
                    + Nueva COPRES
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>
    
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                @livewire('automotores.copres.index.search')
            </div>
        </div>
    </div>
</x-app-layout>
