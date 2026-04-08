<div class="p-4" x-data="cargaAuto(@this)">
 
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-gray-800">Carga automática</h1>
    </div>
 
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-600 mb-1">
            Archivos PRN
            <span class="text-gray-400 font-normal">(múltiple)</span>
        </label>
        <input type="file" multiple accept=".prn,.txt,.csv"
               x-ref="inputArchivos"
               x-on:change="cargarLista"
               :disabled="procesando"
               class="w-full text-sm text-gray-600
                      file:mr-3 file:py-1.5 file:px-3
                      file:rounded file:border-0
                      file:text-sm file:bg-gray-200
                      file:hover:bg-gray-300
                      disabled:opacity-50">
    </div>
 
    @if(count($tabla) > 0)
 
    <table class="w-full text-sm border-collapse mb-4">
        <thead>
            <tr class="bg-gray-100 text-xs text-gray-600 uppercase text-left">
                <th class="px-3 py-2 border w-8">#</th>
                <th class="px-3 py-2 border">Archivo</th>
                <th class="px-3 py-2 border text-center w-32">Estado</th>
                <th class="px-3 py-2 border text-center w-40">Registros</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tabla as $nombre => $fila)
            <tr class="border-b
                {{ $fila['estado'] === 'ejecutando' ? 'bg-blue-50'  : '' }}
                {{ $fila['estado'] === 'finalizado' ? 'bg-green-50' : '' }}
                {{ $fila['estado'] === 'error'      ? 'bg-red-50'   : '' }}">
 
                <td class="px-3 py-2 border text-gray-400 text-xs">{{ $loop->iteration }}</td>
 
                <td class="px-3 py-2 border font-mono text-xs">{{ $fila['nombre'] }}</td>
 
                <td class="px-3 py-2 border text-center">
                    @if($fila['estado'] === 'pendiente')
                        <span class="text-xs text-gray-400">Pendiente</span>
                    @elseif($fila['estado'] === 'ejecutando')
                        <span class="text-xs text-blue-600 font-semibold flex items-center justify-center gap-1">
                            <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            Ejecutando
                        </span>
                    @elseif($fila['estado'] === 'finalizado')
                        <span class="text-xs text-green-700 font-semibold">Finalizado</span>
                    @elseif($fila['estado'] === 'error')
                        <span class="text-xs text-red-600 font-semibold">Error ⚠</span>
                    @endif
                </td>
 
                <td class="px-3 py-2 border text-center font-mono text-xs">
                    @if($fila['estado'] === 'finalizado')
                        <span class="text-green-700">{{ $fila['insertados'] }}</span>
                        @if($fila['errores'] > 0)
                            / <span class="text-red-500">{{ $fila['errores'] }} err</span>
                        @endif
                    @elseif($fila['estado'] === 'error')
                        <span class="text-red-400 text-xs">{{ $fila['mensaje'] }}</span>
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
 
    @php
        $total      = count($tabla);
        $procesados = collect($tabla)->whereIn('estado', ['finalizado', 'error'])->count();
        $pct        = $total > 0 ? round($procesados / $total * 100) : 0;
    @endphp
    <div class="mb-4">
        <div class="flex justify-between text-xs text-gray-500 mb-1">
            <span>Progreso general</span>
            <span>{{ $procesados }}/{{ $total }}</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                 style="width: {{ $pct }}%"></div>
        </div>
    </div>
 
    <div class="flex gap-2">
        <button x-on:click="iniciarProceso"
                x-bind:disabled="procesando"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50">
            <span x-show="!procesando">Procesar todo</span>
            <span x-show="procesando">Procesando...</span>
        </button>
        <button wire:click="resetTodo"
                x-bind:disabled="procesando"
                class="px-4 py-2 bg-gray-400 text-white text-sm rounded hover:bg-gray-500 disabled:opacity-50">
            Limpiar
        </button>
    </div>
 
    @endif
 
</div>
 
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cargaAuto', (wire) => ({
        procesando: false,
        archivosJS: [],
 
        cargarLista(event) {
            this.archivosJS = Array.from(event.target.files);
            wire.inicializarTabla(this.archivosJS.map(f => f.name));
        },
 
        leerBase64(archivo) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload  = () => resolve(reader.result.split(',')[1]);
                reader.onerror = reject;
                reader.readAsDataURL(archivo);
            });
        },
 
        async iniciarProceso() {
            this.procesando = true;
 
            for (const archivo of this.archivosJS) {
                const nombre = archivo.name;
 
                if (wire.tabla[nombre]?.estado !== 'pendiente') continue;
 
                const base64 = await this.leerBase64(archivo);
                await wire.procesarArchivo(nombre, base64);
            }
 
            this.procesando = false;
        },
    }));
});
</script>