<x-app-layout>
    <x-page-header title="Proveedores Eliminados" />

    {{-- Panel de tipos de documentos compacto --}}
    <div class="bg-gray-100 border border-gray-200 rounded-md my-3 p-2">
        <div class="flex flex-wrap gap-1 justify-center">
            @foreach (App\Models\Proveedores\DocumentoTipo::orderBy('codigo')->get() as $documentoTipo)
                <div class="inline-flex items-center" 
                     x-data="{ show: false }" 
                     @mouseenter="show = true" 
                     @mouseleave="show = false">
                    <span class="text-xs font-semibold px-2 py-1 rounded text-white text-center min-w-[35px]"
                        style="background-color: #{{ substr(md5($documentoTipo->codigo), 0, 6) }}">
                        {{ $documentoTipo->codigo }}
                    </span>
                    <div x-show="show" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute z-10 bg-gray-800 text-white text-xs rounded px-2 py-1 mt-8 whitespace-nowrap">
                        {{ $documentoTipo->nombre }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    {{-- Contenedor principal más compacto --}}
    <div class="mb-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            @livewire('proveedores.proveedors.index.eliminados', [], key(microtime(true)))
        </div>
    </div>
</x-app-layout>
