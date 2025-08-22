<html>
<head>
    <style>
        @page {
            margin: 0cm 0cm;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        body {
            margin: 3cm 2cm 2cm;
        }

        header {
            position: fixed;
            padding: 30px;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            background-color: #1a264e;
            color: white;
            text-align: center;
            line-height: 30px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #1a264e;
            color: white;
            text-align: center;
            line-height: 35px;
        }
    </style>
</head>
<body>
<header>
    <img src="img/logo-pdf.png" alt="" style="height: 50px;">
</header>
<main>
    <div>
        <h1>
            {{$proveedor->razonsocial}}
        </h1>
        <div>
            <h2>
                Datos Generales
            </h2>
            <div> 
                Razón social <strong>{{$proveedor->razonsocial}}</strong>
            </div>
            <div>
                Nombre Fantasía <strong>{{$proveedor->fantasia}}</strong>
            </div>
            <div>
                CUIT <strong>{{$proveedor->cuit}}</strong>
            </div>
            <div>
                Correo Institucional <strong>{{$proveedor->correo}}</strong>
            </div>
            <hr>
            <div>
                Teléfono <strong>{{$proveedor->telefono}}</strong>
            </div>
            <div>
                Horario <strong>{{$proveedor->horario}}</strong>
            </div>
            <div>
                Sitio Web <strong>{{$proveedor->webpage}}</strong>
            </div>
            <hr>
            <div>
                Proveedor ID <strong>{{$proveedor->id}}</strong>
            </div>
            <div>
                Estado <strong>Nivel {{$proveedor->estado_id}}</strong>
            </div>
            <hr>
            <h2>
                Documentos
            </h2>
            @if(count($proveedor->documentos) == 0)
            <i>No existen documentos asociados al proveedor</i>
            @endif
            @php
            $td_id = 0;
            @endphp
            @foreach($proveedor->documentos as $documento)
                @if($documento->documentoTipo->id != $td_id)
                    <hr>
                    <div>
                        <div>
                            {{$documento->documentoTipo->codigo}} - 
                            <strong>{{$documento->documentoTipo->nombre}}</strong>
                        </div>
                    </div>
                    <div>
                        <div>
                            Cargado 
                            <strong>{{ \Carbon\Carbon::parse($documento->created_at)->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                    @if($documento->documentoTipo->vencimiento)
                    <div>
                        Vencimiento
                        <strong>
                            @if ($documento->vencimiento)
                                {{ \Carbon\Carbon::parse($documento->vencimiento)->format('d/m/Y') }}
                                @if (\Carbon\Carbon::now()->addYear() > $documento->vencimiento)
                                    @if ($documento->vencimiento->isPast())
                                        <span class="text-white bg-red-400 px-1 rounded-full text-xs"
                                        title="Documentación Vencida">Vencido</span>
                                    @endif
                                @endif
                            @else
                            <i>Sin especificar</i>
                            @endif
                        </strong>
                    </div>
                    @endif
                    
                @else
                    <div>
                        <div>
                            {{ \Carbon\Carbon::parse($documento->created_at)->format('d/m/Y') }}
                        </div>
                        <div>
                            <i>Archivado</i>
                        </div>
                    </div>
                    <hr>
                @endif
                @php
                $td_id = $documento->documentoTipo->id;
                @endphp
            @endforeach
            <h2>
                Apoderados
            </h2>
            @if(count($proveedor->apoderados) == 0)
            <i>No existen apoderados asociados al proveedor</i>
            @endif
            @foreach($proveedor->apoderados->sortBy('tipo') as $apoderado)
                    <hr>
                    <div>
                        <div>
                            @if($apoderado->tipo == 'apoderado')
                            Apoderado
                            @else
                            Representante Legal: <strong>{{$apoderado->nombre}}</strong>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div>
                            Cargado 
                            <strong>{{ \Carbon\Carbon::parse($apoderado->lastDocumento->created_at)->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                    @if($apoderado->lastDocumento->vencimiento)
                    <div>
                        Vencimiento
                        <strong>
                            @if ($apoderado->lastDocumento->vencimiento)
                                {{ \Carbon\Carbon::parse($apoderado->lastDocumento->vencimiento)->format('d/m/Y') }}
                                @if (\Carbon\Carbon::now()->addYear() > $apoderado->lastDocumento->vencimiento)
                                    @if ($apoderado->lastDocumento->vencimiento->isPast())
                                        <span class="text-white bg-red-400 px-1 rounded-full text-xs"
                                        title="Documentación Vencida">Vencido</span>
                                    @endif
                                @endif
                            @else
                            <i>Sin especificar</i>
                            @endif
                        </strong>
                    </div>
                    @endif
            @endforeach
        </div>
        <hr>
        <div>
            <div>
                <h2>
                    Datos de Contacto
                </h2>
                <p>
                    @if(count($proveedor->contactos) == 0)
                    <i>No existen contactos asociados al proveedor</i>
                    @endif
                    @foreach($proveedor->contactos as $contacto)
                        <div>
                            <div>
                                @if($contacto->nombre)
                                <div>
                                    Nombre
                                    <strong>{{$contacto->nombre}}</strong>
                                </div>
                                @endif
                                @if($contacto->telefono)
                                <div>
                                    Teléfono
                                    <strong>{{$contacto->telefono}}</strong>
                                </div>
                                @endif
                                @if($contacto->correo)
                                <div>
                                    Correo
                                    <strong>{{$contacto->correo}}</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </p>
            </div>
            <div>
                <h2>
                    Direcciones
                </h2>
                <p>
                    @if(count($proveedor->direcciones) == 0)
                    <i>No existen direcciones asociadas al proveedor</i>
                    @endif
                    @foreach($proveedor->direcciones as $direccion)
                    <div>
                        <div>
                            <div>
                                Tipo
                                <div>
                                    {{$direccion->tipo}}
                                </div>
                            </div>
                            <div>
                                Dirección
                                <strong>
                                    {{$direccion->calle}} #{{$direccion->altura}}, {{$direccion->piso}}, {{$direccion->departamento}}
                                </strong>
                            </div>
                            <div>
                                Ciudad
                                <strong>{{$direccion->ciudad}} ({{$direccion->codigopostal}})</strong>
                            </div>
                            <div>
                                Provincia
                                <strong>{{$direccion->provincia}}, {{$direccion->pais}}</strong>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </p>
            </div>
            <div>
                <h2>
                    Registros Bancarios
                </h2>
                <p>
                    @if(count($proveedor->datos_bancarios) == 0)
                    <i>No existen datos bancarios asociados al proveedor</i>
                    @endif
                    @foreach($proveedor->datos_bancarios as $cuenta)
                    <div>
                        <div>
                            <div>
                                Tipo de Cuenta
                                <strong>
                                    {{$cuenta->tipocuenta}}
                                </strong>
                            </div>
                            <div>
                                Número de Cuenta
                                <strong>{{$cuenta->numerocuenta}}</strong>
                            </div>
                            <div>
                                CBU/CVU
                                <strong>{{$cuenta->cbu}}</strong>
                            </div>
                            <div>
                                Alias
                                <strong>{{$cuenta->alias}}</strong>
                            </div>
                            <div>
                                Banco
                                <strong>{{$cuenta->banco}}</strong>
                            </div>
                            <div>
                                Sucursal
                                <strong>{{$cuenta->sucursal}}</strong>
                            </div>
                            <div>
                                Fecha
                                <strong>{{ \Carbon\Carbon::parse($cuenta->created_at)->format('d/m/Y, H:i') }}hs.</strong>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</main>
<footer>
    Fecha de creación del PDF: <strong>{{ \Carbon\Carbon::now()->format('d-m-Y H:i')}}</strong>
</footer>
</body>
</html>