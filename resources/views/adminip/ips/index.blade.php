<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-8 titulo-index">
                        Listado IPS
                    </div>
                    <div class="col-span-2 text-center">
                        @can('AdminIP/IPS/Crear')
                        @livewire('adminip.ips.index.crear', [], key(microtime(true)))
                        @endcan
                    </div>
                </div>
                @livewire('adminip.ips.index.table-search', [], key(microtime(true)))
            </div>
        </div>
    </div>
</x-app-layout>
