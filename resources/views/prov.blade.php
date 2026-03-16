<x-guest-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Proveedores</h1>
        
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Proveedor</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Cuit</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Subrubros</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($proveedores as $proveedor)
                        @if($proveedor->cuit == 123456789)
                            @continue
                        @endif
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $proveedor->razonsocial }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $proveedor->cuit }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $proveedor->subrubros->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-guest-layout>