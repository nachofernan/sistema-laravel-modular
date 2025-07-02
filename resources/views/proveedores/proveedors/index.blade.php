<x-app-layout>
    <div class="border-b border-gray-200 rounded-lg bg-gray-200 mx-auto w-3/4 px-0 py-2 mt-5 text-sm text-center">
        @foreach (App\Models\Proveedores\DocumentoTipo::orderBy('codigo')->get() as $documentoTipo)
            <div class="inline-flex py-1 items-center">
                <span class="text-xs uppercase font-bold px-2 py-1 rounded text-gray-100 mr-1"
                    title="{{ $documentoTipo->nombre }}"
                    style="background-color:
                @php
                echo '#'.substr(md5($documentoTipo->codigo), 0, 6); @endphp
                ">{{ $documentoTipo->codigo }}</span>
                <span class="text-xs uppercase mr-1">
                    {{ $documentoTipo->nombre }}
                </span>
            </div>
        @endforeach
    </div>
    <div class="w-full xl:w-12/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                @livewire('proveedores.proveedors.index.search', [], key(microtime(true)))
            </div>
        </div>
    </div>
</x-app-layout>
