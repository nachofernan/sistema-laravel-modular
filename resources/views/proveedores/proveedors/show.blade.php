<x-app-layout>
    <div class="w-full xl:w-11/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg overflow-hidden ">
            @if ($proveedor->litigio)
                <div class="text-sm px-4 bg-red-700 font-bold text-white rounded-t text-center w-full py-2">Proveedor en Litigio</div>
            @endif
            <div class="flex justify-between border-b bg-gradient-to-r from-blue-700 to-blue-400 p-6">
                <div>
                    <div class="text-2xl font-bold text-white">
                        {{ $proveedor->razonsocial }}
                    </div>
                    <div class="text-blue-100">
                        CUIT: {{ $proveedor->cuit }} - {{ $proveedor->estado->estado }}
                    </div>
                </div>
                <div class="text-sm flex justify-end gap-4 items-center">
                    @livewire('proveedores.proveedors.show.notificar-vencimiento', ['proveedor' => $proveedor])
                    <div>
                        <a 
                            href="{{ route('proveedores.pdfbb', $proveedor) }}" 
                            class="bg-white hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded flex items-center"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 mr-1">
                            <path fill-rule="evenodd" d="M4 2a1.5 1.5 0 0 0-1.5 1.5v9A1.5 1.5 0 0 0 4 14h8a1.5 1.5 0 0 0 1.5-1.5V6.621a1.5 1.5 0 0 0-.44-1.06L9.94 2.439A1.5 1.5 0 0 0 8.878 2H4Zm4 3.5a.75.75 0 0 1 .75.75v2.69l.72-.72a.75.75 0 1 1 1.06 1.06l-2 2a.75.75 0 0 1-1.06 0l-2-2a.75.75 0 0 1 1.06-1.06l.72.72V6.25A.75.75 0 0 1 8 5.5Z" clip-rule="evenodd" />
                        </svg>                          
                        Descargar PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="grid grid-cols-2 gap-5">
                    <div class="col">
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
                                    <h3 class="font-medium text-gray-700">
                                        Datos Generales
                                    </h3>
                                    @canany(['Proveedores/Proveedores/Editar', 'Proveedores/Proveedores/EditarEstado'])
                                    <div>
                                        <a href="{{ route('proveedores.proveedors.edit', $proveedor) }}" class="text-blue-700 hover:underline text-sm">Editar</a>
                                    </div>
                                    @endcanany
                            </div>
                            <div class="p-6 space-y-3 p-4 text-sm">
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">Razón Social</span>
                                    <span class="text-gray-600">{{ $proveedor->razonsocial }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">Nombre de Fantasía</span>
                                    <span class="text-gray-600">{{ $proveedor->fantasia ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">CUIT</span>
                                    <span class="text-gray-600">{{ $proveedor->cuit }}</span>
                                </div>
                                <div class="flex justify-between border-b-2 pb-2">
                                    <span class="font-medium text-gray-800">Correo Institucional</span>
                                    <span class="text-gray-600">{{ $proveedor->correo }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">Cargado</span>
                                    <span class="text-gray-600">{{ $proveedor->created_at->format('d-m-Y') }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">Número ID</span>
                                    <span class="text-gray-600">{{ $proveedor->id }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">Estado</span>
                                    <span class="text-gray-600">{{ $proveedor->estado->estado }}</span>
                                </div>
                                <div class="flex justify-between border-b-2 pb-2">
                                    <span class="font-medium text-gray-800">Registrado en Portal</span>
                                    <span class="text-gray-600">{{ $proveedor->proveedor_externo ? 'Si' : 'No' }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">Teléfono</span>
                                    <span class="text-gray-600">{{ $proveedor->telefono ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">Horario</span>
                                    <span class="text-gray-600">{{ $proveedor->horario ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-800">Sitio Web</span>
                                    @if ($proveedor->webpage)
                                    <a href="{{ $proveedor->webpage }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $proveedor->webpage }}
                                    </a>
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
                                <h3 class="font-medium text-gray-700">
                                    Documentación
                                </h3>
                                @can('Proveedores/Documentos/Crear')
                                    @livewire('proveedores.proveedors.show.cargar-documento', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))
                                @endcan
                            </div>
                            <div class="space-y-3 p-4">
                                @if (count($proveedor->documentos) == 0)
                                    <div class="text-gray-500 italic text-center">No existen documentos asociados al proveedor</div>
                                @else
                                    @php $td_id = 0; @endphp
                                    @foreach ($proveedor->documentos as $documento)
                                        @if ($documento->documentoTipo->id != $td_id)
                                            <div class="flex justify-between border-b pb-2">
                                                <span class="text-gray-600">
                                                    <span class="font-bold text-gray-900">{{ $documento->documentoTipo->codigo }}</span>
                                                    -
                                                    {{ $documento->documentoTipo->nombre }}
                                                </span>
                                                @livewire('proveedores.proveedors.show.ver-documentacion-modal', ['proveedor' => $proveedor, 'documentoTipo' => $documento->documentoTipo], key($proveedor->id . microtime(true)))
                                            </div>
                                            <div class="flex justify-between border-b-4 pb-2 text-sm">
                                                <a href="{{Storage::disk('proveedores')->url($documento->file_storage)}}" class="text-blue-600 hover:underline" target="_blank">Descargar</a>
                                                <div class="text-gray-600 flex items-center gap-2">
                                                    Vencimiento:
                                                    @if ($documento->vencimiento)
                                                        {{$documento->vencimiento->format('d-m-Y')}}
                                                        @if (now()->addYear() > $documento->vencimiento)
                                                            @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::create($documento->vencimiento), false) < 0)
                                                                <span class="text-red-500"
                                                                title="Documentación Vencida">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                                        <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                                    </svg>                                                  
                                                                </span>
                                                            @else
                                                                @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::create($documento->vencimiento), false) < 30)
                                                                <span class="text-yellow-500"
                                                                title="Documentación a Vencer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                                    <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                                </svg>                                                  
                                                                </span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="text-gray-500">Sin especificar</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @php $td_id = $documento->documentoTipo->id; @endphp
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                    
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
                                <h3 class="font-medium text-gray-700">
                                    Representante Legal y Apoderados
                                </h3>
                                @can('Proveedores/Proveedores/Editar')
                                    @livewire('proveedores.proveedors.show.cargar-apoderados', [
                                        'proveedor' => $proveedor
                                    ], key($proveedor->id . microtime(true)))
                                @endcan
                            </div>
                        
                            <div class="space-y-2 px-4 py-4">
                                <!-- Lista de Apoderados -->
                                @forelse ($proveedor->apoderados->where('tipo', 'representante')->sortByDesc('activo') as $key => $apoderado)
                                    <div class="bg-white {{ $key ? 'border-b-4 border-gray-200' : '' }}">
                                        <!-- Información Principal -->
                                        <div>
                                            <div class="flex items-center justify-between border-b py-1">
                                                <div class="flex-grow">
                                                    <div class="text-sm text-gray-500">
                                                        {{ $apoderado->tipo == 'apoderado' ? 'Apoderado' : 'Representante Legal' }}
                                                        -
                                                        {{ $apoderado->activo ? 'Activo' : 'Histórico' }}
                                                    </div>
                                                    <div class="font-medium">{{ $apoderado->nombre }}</div>
                                                </div>
                                                @can('Proveedores/Proveedores/Editar')
                                                    <div class="ml-4">
                                                        @livewire('proveedores.proveedors.show.editar-apoderados', [
                                                            'apoderado' => $apoderado
                                                        ], key($apoderado->id.microtime(true)))
                                                    </div>
                                                @endcan
                                            </div>
                            
                                            <!-- Sección de Documentos -->
                                            <div class="text-sm">
                                                @forelse ($apoderado->documentos->sortByDesc('created_at') as $documento)
                                                @if (!$documento->validacion->validado)
                                                    @continue
                                                @endif
                                                    <div class="flex justify-between gap-4 py-1 border-b border-gray-200">
                                                        <div>
                                                            <a 
                                                            href="{{Storage::disk('proveedores')->url($documento->file_storage)}}" 
                                                            class="link-azul" target="_blank">
                                                                Descargar</a>
                                                            @if (!$loop->first)
                                                                <span class="text-xs">
                                                                    (archivo viejo)
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-gray-500">Vencimiento: {{ $documento->vencimiento ? $documento->vencimiento->format('d-m-Y') : 'Sin especificar'}}</div>
                                                    </div>
                                                @empty
                                                    <p class="text-gray-500 italic">No hay documentos cargados</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                <div class="text-gray-500 italic text-center">No hay Representantes Legales asociados al proveedor</div>
                                @endforelse
                            </div>
                            <h3 class="font-medium text-gray-700 px-4 text-xl">
                                Apoderados
                            </h3>
                            <div class="space-y-2 px-4 py-4">
                                <!-- Lista de Apoderados -->
                                @forelse ($proveedor->apoderados->where('tipo', 'apoderado')->sortByDesc('activo') as $key => $apoderado)
                                    <div class="bg-white {{ $key ? 'border-b-4 border-gray-200' : '' }}">
                                        <!-- Información Principal -->
                                        <div>
                                            <!-- Sección de Documentos -->
                                            <div class="text-sm">
                                                <div class="flex justify-between gap-4 py-1 border-b border-gray-200">
                                                    <div class="flex items-center gap-1">
                                                        <a 
                                                        href="{{Storage::disk('proveedores')->url($apoderado->documentos->first()->file_storage)}}" 
                                                        class="link-azul" target="_blank">
                                                            Descargar</a>
                                                        <div class="text-sm text-gray-500">
                                                            -
                                                            {{ $apoderado->activo ? 'Activo' : 'Histórico' }}
                                                        </div>
                                                    </div>
                                                    @can('Proveedores/Proveedores/Editar')
                                                        <div class="ml-4">
                                                            @livewire('proveedores.proveedors.show.editar-apoderados', [
                                                                'apoderado' => $apoderado
                                                            ], key($apoderado->id.microtime(true)))
                                                        </div>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                <div class="text-gray-500 italic text-center">No hay apoderados asociados al proveedor</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
                                <h3 class="font-medium text-gray-700">
                                    Contactos
                                </h3>
                                @can('Proveedores/Proveedores/Editar')
                                    @can('Proveedores/Proveedores/Editar')
                                        @livewire('proveedores.proveedors.show.cargar-contacto', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))
                                    @endcan
                                @endcan
                            </div>
                            <div class="p-4">
                                @foreach ($proveedor->contactos as $key => $contacto)
                                    <div class="grid-datos-show gap-y-2 {{$key ? 'pt-2 mt-2 border-t' : ''}}">
                                        <div class="atributo-show">
                                            Nombre
                                        </div>
                                        <div class="valor-show grid grid-cols-10">
                                            <div class="col-span-8">
                                                {{ $contacto->nombre }}
                                            </div>
                                            <div class="col-span-2 text-xs">
                                                @can('Proveedores/Proveedores/Editar')
                                                @livewire('proveedores.proveedors.show.borrar-contacto', ['contacto' => $contacto], key($contacto->id.microtime(true)))
                                                @endcan
                                            </div>
                                        </div>
                                        <div class="atributo-show">
                                            Teléfono
                                        </div>
                                        <div class="valor-show">
                                            {{ $contacto->telefono }}
                                        </div>
                                        <div class="atributo-show">
                                            Correo
                                        </div>
                                        <div class="valor-show">
                                            <a href="mailto:{{ $contacto->correo }}" target="_blank"
                                                class="link-azul">{{ $contacto->correo }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
                                <h3 class="font-medium text-gray-700">
                                    Direcciones
                                </h3>
                                @can('Proveedores/Proveedores/Editar')
                                    @can('Proveedores/Proveedores/Editar')
                                @livewire('proveedores.proveedors.show.cargar-direccion', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))
                                    @endcan
                                @endcan
                            </div>
                            <div class="p-4">
                                @foreach ($proveedor->direcciones as $key => $direccion)
                                    <div class="grid-datos-show gap-y-2 {{$key ? 'pt-2 mt-2 border-t' : ''}}">
                                        <div class="atributo-show">
                                            Tipo
                                        </div>
                                        <div class="valor-show grid grid-cols-10">
                                            <div class="col-span-8">
                                                {{ $direccion->tipo }}
                                            </div>
                                            <div class="col-span-2 text-xs">
                                                @can('Proveedores/Proveedores/Editar')
                                                @livewire('proveedores.proveedors.show.borrar-direccion', ['direccion' => $direccion], key($direccion->id.microtime(true)))
                                                @endcan
                                            </div>
                                        </div>
                                        <div class="atributo-show">
                                            Dirección
                                        </div>
                                        <div class="valor-show">
                                            {{ $direccion->calle }} #{{ $direccion->altura }}, {{ $direccion->piso }},
                                            {{ $direccion->departamento }}
                                        </div>
                                        <div class="atributo-show">
                                        </div>
                                        <div class="valor-show">
                                            {{ $direccion->ciudad }} ({{ $direccion->codigopostal }}),
                                            {{ $direccion->provincia }}, {{ $direccion->pais }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="subtitulo-show pt-4">
                            <div class="grid grid-cols-2">
                                <div class="col">
                                    Registros Bancarios
                                </div>
                                <div class="col">
                                    @can('Proveedores/Proveedores/Editar')
                                    @livewire('proveedores.proveedors.show.cargar-bancario', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @foreach ($proveedor->datos_bancarios as $key => $bancario)
                            <div class="grid-datos-show gap-y-2 {{$key ? 'pt-2 mt-2 border-t' : ''}}">
                                <div class="atributo-show">
                                    Tipo de Cuenta
                                </div>
                                <div class="valor-show">
                                    {{ $bancario->tipocuenta }}
                                </div>
                                <div class="atributo-show">
                                    Número de Cuenta
                                </div>
                                <div class="valor-show">
                                    {{ $bancario->numerocuenta }}
                                </div>
                                <div class="atributo-show">
                                    CBU/CVU
                                </div>
                                <div class="valor-show">
                                    {{ $bancario->cbu }}
                                </div>
                                <div class="atributo-show">
                                    Alias
                                </div>
                                <div class="valor-show">
                                    {{ $bancario->alias }}
                                </div>
                                <div class="atributo-show">
                                    Banco
                                </div>
                                <div class="valor-show">
                                    {{ $bancario->banco }}
                                </div>
                                <div class="atributo-show">
                                    Sucursal
                                </div>
                                <div class="valor-show">
                                    {{ $bancario->sucursal }}
                                </div>
                                <div class="atributo-show">
                                    Fecha
                                </div>
                                <div class="valor-show">
                                    {{ \Carbon\Carbon::create($bancario->created_at)->format('d/m/Y, H:i') }}hs.
                                </div>
                            </div>
                        @endforeach --}}
                        @livewire('proveedores.proveedors.show.rubros-edit', ['proveedor' => $proveedor], key($proveedor->id . microtime(true)))

                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
                                <h3 class="font-medium text-gray-700">
                                    Concursos Invitados
                                </h3>
                            </div>
                            <div class="space-y-3 p-4">
                                @foreach ($proveedor->invitaciones->sortByDesc('created_at') as $invitacion)
                                    <div class="flex justify-between border-b pb-2 text-sm">
                                        <span class="text-gray-600">
                                            <a href="{{ route('concursos.concursos.show', $invitacion->concurso) }}" class="text-blue-600 hover:underline">
                                                {{$invitacion->concurso->nombre}} - #{{$invitacion->concurso->numero}}
                                            </a>
                                        </span>
                                        <span class="px-4 font-medium">
                                            @switch($invitacion->intencion)
                                                @case(0)
                                                    <span class="text-yellow-500">Nunca contestó</span>
                                                    @break
                                                @case(1)
                                                    <span class="text-blue-500">Hubo intención</span>
                                                    @break
                                                @case(2)
                                                    <span class="text-red-500">Hubo negativa</span>
                                                    @break
                                                @case(3)
                                                    <span class="text-green-500">Presentó Oferta</span>
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>
