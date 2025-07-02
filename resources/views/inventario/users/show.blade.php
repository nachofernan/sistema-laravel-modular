<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-12">
                    <div class="titulo-show">
                        {{ $user->realname }} - ({{ $user->legajo }})
                    </div>
                </div>
            </div>

            <div class="block w-full overflow-x-auto py-5 px-5">
                @livewire('inventario.users.show.table-search', ['user' => $user], key($user->id . microtime(true)))
            </div>
        </div>
    </div>
</x-app-layout>
