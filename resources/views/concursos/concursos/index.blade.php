<x-app-layout>
    <x-page-header title="Gestión de Concursos">
        <x-slot:actions>
            @can('Concursos/Concursos/Crear')
                <a href="{{ route('concursos.concursos.create') }}"
                    class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    + Nuevo Concurso
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="px-4 sm:px-6 lg:px-8">
        @livewire('concursos.concurso.index.search')
    </div>
</x-app-layout>
