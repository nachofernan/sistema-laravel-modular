<x-guest-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Rubros Proveedores</h1>
        
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Rubro</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Subrubro</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Proveedor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($rubros as $rubro)
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <td colspan="3" class="px-6 py-3 text-sm font-medium text-gray-900"></td>
                        </tr>
                        @foreach($rubro->subrubros as $subkey => $subrubro)
                            @if($subrubro->proveedores->isEmpty())
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $subkey === 0 ? $rubro->nombre : '' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $subrubro->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 italic">(sin proveedores)</td>
                                </tr>
                            @else
                                @foreach($subrubro->proveedores as $prokey => $prov)
                                @if($prov->cuit == 123456789)
                                    @if($subrubro->proveedores->count() == 1)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $subkey === 0 ? $rubro->nombre : '' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $subrubro->nombre }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 italic">(sin proveedores)</td>
                                    </tr>
                                    @endif
                                    @continue
                                @endif
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $subkey === 0 && $prokey === 0 ? $rubro->nombre : '' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $prokey === 0 ? $subrubro->nombre : '' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <span class="font-medium">{{ $prov->razonsocial }}</span>
                                            <span class="text-gray-500 text-xs ml-2">(cuit: {{ $prov->cuit }})</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-guest-layout>