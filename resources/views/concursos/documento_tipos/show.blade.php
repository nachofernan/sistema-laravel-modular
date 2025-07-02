<x-app-layout>
    <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8">
                    <div class="titulo-show">
                        {{ $documentoTipo->codigo }} - {{ $documentoTipo->nombre }}
                    </div>
                </div>
                <div class="col-span-4 text-right pt-3 text-sm">
                    @can('Proveedores/DocumentoTipos/Editar')
                        <a href="{{ route('proveedores.documento_tipos.edit', $documentoTipo) }}" class="boton-celeste">Editar</a>
                    @endcan
                </div>
            </div>
            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="subtitulo-show">
                    Datos Generales
                </div>
                <div class="grid-datos-show">
                    <div class="atributo-show">
                        Código
                    </div>
                    <div class="valor-show">
                        <span class="text-xs uppercase font-bold px-2 py-1 rounded text-gray-100 mr-1"
                            style="background-color:
                                    @php
echo '#'.substr(md5($documentoTipo->codigo), 0, 6); @endphp
                                    ">
                            {{ $documentoTipo->codigo }}
                        </span>
                    </div>
                    <div class="atributo-show">
                        Nombre
                    </div>
                    <div class="valor-show">
                        {{ $documentoTipo->nombre }}
                    </div>
                    <div class="atributo-show">
                        ¿Tiene Vencimiento?
                    </div>
                    <div class="valor-show">
                        {{ $documentoTipo->vencimiento ? 'Si' : 'No' }}
                    </div>
                    <div class="atributo-show">
                        Usuario Validador
                    </div>
                    <div class="valor-show">
                        {{ $documentoTipo->validador ? $documentoTipo->validador->realname : 'No Tiene' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
