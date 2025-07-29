<x-app-layout>
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                @livewire('home.buscador', [], key(microtime(true)))
            </div>
        </div>
    </div>
</x-app-layout>