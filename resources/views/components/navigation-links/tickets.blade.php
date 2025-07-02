

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('Tickets/Tickets/Ver')
                    <x-nav-link href="{{ route('tickets.tickets.index') }}" :active="request()->routeIs('tickets.tickets.*')">
                        Tickets
                    </x-nav-link>
                    @endcan
                </div>

            