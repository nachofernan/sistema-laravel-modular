<head>
    <style>
        @page {
            margin: 0;
            font-family: Helvetica, Arial, sans-serif;
        }

        body {
            margin: 2cm 1.5cm 1.5cm;  /* Reducidos los márgenes */
            color: #2d3748;
            line-height: 1.4;  /* Reducido el interlineado */
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1a264e;
            color: white;
            text-align: center;
            padding: 10px;  /* Reducido el padding */
        }

        header img {
            height: 40px;  /* Logo más pequeño */
            margin-bottom: 5px;
        }

        h2 {
            color: #2d4373;
            font-size: 16px;  /* Título más pequeño */
            margin-top: 15px;  /* Menos espacio superior */
            padding: 5px 10px;
            background: #f7fafc;
            border-left: 4px solid #1a264e;
        }

        .seccion {
            padding: 10px;  /* Menos padding */
            background: #ffffff;
            border-radius: 5px;
        }

        .campo {
            margin: 3px 0;  /* Reducido el margen */
            padding: 4px 0;  /* Reducido el padding */
            border-bottom: 1px solid #e2e8f0;
            display: flex;  /* Nuevo: layout flexible */
            flex-wrap: wrap;
            gap: 5px 15px;  /* Espacio entre elementos */
        }

        .etiqueta {
            color: #4a5568;
            font-size: 0.85em;  /* Texto más pequeño */
            min-width: 120px;  /* Ancho fijo para etiquetas */
        }

        .proveedor-card {
            background: #f8fafc;
            padding: 8px 12px;  /* Reducido el padding */
            margin: 8px 0;
            border-radius: 5px;
            border-left: 3px solid #1a264e;
            page-break-inside: avoid;
            display: inline-block;  /* Nuevo: permite que los cards se acomoden lado a lado */
            width: calc(50% - 10px);  /* Dos cards por fila */
            vertical-align: top;
        }

        .prorroga-item {
            background: #edf2f7;
            padding: 4px 8px;  /* Reducido el padding */
            margin: 3px 0;
            border-radius: 4px;
            font-size: 0.9em;  /* Texto más pequeño */
        }

        .contacto-item {
            padding: 4px 0;  /* Reducido el padding */
            font-size: 0.9em;  /* Texto más pequeño */
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1.5cm;  /* Pie de página más pequeño */
            background: #1a264e;
            color: white;
            text-align: center;
            line-height: 1.5cm;
            font-size: 0.8em;
        }

        /* Nuevos estilos para optimizar el layout */
        .proveedores-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .seccion-proveedores {
            page-break-before: always;
        }

        strong {
            font-weight: bold;
            margin-left: 20px;
        }

        .salto-pagina {
            page-break-before: always;
        }

        table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        thead th:nth-child(1) {
            width: 35%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        /* Responsive: en pantallas pequeñas, un proveedor por fila */
        @media (max-width: 768px) {
            .proveedor-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="img/logo-pdf-cdp.png" alt="Logo">
    </header>

    <main>
        <div class="seccion">
            <h2>Datos Generales</h2>
            
            <div class="campo">
                <span class="etiqueta">Nombre del Concurso:</span><br>
                <strong>{{$concurso->nombre}}</strong>
            </div>

            <div class="campo">
                <span class="etiqueta">Número:</span><br>
                <strong>#{{$concurso->numero}}</strong>
            </div>

            <div class="campo">
                <span class="etiqueta">Descripción:</span><br>
                <strong>{{$concurso->descripcion}}</strong>
            </div>

            <div class="campo">
                <span class="etiqueta">Sedes:</span><br>
                @foreach ($concurso->sedes as $sede)
                <strong>{{$sede->nombre}}</strong><br>
                @endforeach
            </div>

            <div class="campo">
                <span class="etiqueta">Período:</span><br>
                <strong>Inicio: {{$concurso->fecha_inicio->format('d-m-Y H:i')}}</strong><br>
                <strong>Cierre: {{$concurso->fecha_cierre->format('d-m-Y H:i')}}</strong>
            </div>

            <div class="campo">
                <span class="etiqueta">Prórrogas:</span><br>
                @foreach ($concurso->prorrogas as $key => $prorroga)
                <div class="prorroga-item">
                    <strong>Prórroga {{ $key+1 }}:</strong>
                    {{ $prorroga->fecha_anterior->format('d-m-Y - H:i') }}
                    <span style="margin: 0 10px">></span>
                    {{ $prorroga->fecha_actual->format('d-m-Y - H:i') }}
                </div>
                @endforeach
            </div>

            <div class="campo">
                <span class="etiqueta">Contactos:</span><br>
                @foreach ($concurso->contactos as $contacto)
                <div class="contacto-item">
                    <strong>{{ $contacto->tipo == 'administrativo' ? 'Administrativo' : 'Técnico' }}:</strong>
                    {{ $contacto->nombre }} - {{ $contacto->correo }} - {{ $contacto->telefono }}
                </div>
                @endforeach
            </div>
            @if ($concurso->subrubro)
            <div class="campo">
                <span class="etiqueta">Categorización:</span><br>
                <strong>Rubro:</strong> {{$concurso->subrubro->rubro->nombre}}<br>
                <strong>SubRubro:</strong> {{$concurso->subrubro->nombre}}
            </div>
            @endif
        </div>
        <div class="seccion salto-pagina">
            <h2>Proveedores Invitados ({{count($concurso->invitaciones)}})</h2>
            @if(count($concurso->invitaciones) == 0)
            <i>No existen proveedores asociados al concurso</i>
            @else
            <table style="width: 100%">
                <thead>
                    <tr>
                        <th style="text-align: left;">Razón Social</th>
                        <th style="text-align: center">CUIT</th>
                        <th>Agregado</th>
                        <th>Estado de oferta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($concurso->invitaciones as $invitacion)
                    <tr>
                        <td>{{$invitacion->proveedor->razonsocial}}</td>
                        <td style="text-align: center">{{$invitacion->proveedor->cuit}}</td>
                        <td style="text-align: center">{{$invitacion->created_at->format('d-m-Y')}}</td>
                        <td style="text-align: center">
                            @switch($invitacion->intencion)
                                @case(0)
                                    Invitado
                                    @break
                                @case(1)
                                    Con intención
                                    @break
                                @case(2)
                                    No participará
                                    @break
                                @case(3)
                                    Presentó oferta
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        {{-- <div class="seccion seccion-proveedores">
            <h2>Proveedores Invitados ({{count($concurso->invitaciones)}})</h2>
            @if(count($concurso->invitaciones) == 0)
            <i>No existen proveedores asociados al concurso</i>
            @endif
            <div class="proveedores-grid">
                @foreach($concurso->invitaciones as $invitacion)
                <div class="proveedor-card">
                    <div class="campo">
                        <span class="etiqueta">Razón Social:</span><br>
                        <strong>{{$invitacion->proveedor->razonsocial}}</strong>
                    </div>
                    <div class="campo">
                        <span class="etiqueta">CUIT:</span><br>
                        <strong>{{$invitacion->proveedor->cuit}}</strong>
                    </div>
                    <div class="campo">
                        <span class="etiqueta">Correo:</span><br>
                        <strong>{{$invitacion->proveedor->correo}}</strong>
                    </div>
                    <div class="campo">
                        <span class="etiqueta">Agregado al concurso:</span><br>
                        <strong>{{$invitacion->created_at->format('d-m-Y')}}</strong>
                    </div>
                    <div class="campo">
                        <span class="etiqueta">Estado de oferta:</span><br>
                        <strong>
                            @switch($invitacion->intencion)
                                @case(0)
                                    Invitado
                                    @break
                                @case(1)
                                    Con intención de participar
                                    @break
                                @case(2)
                                    No participará
                                    @break
                                @case(3)
                                    Presentó oferta
                                    @break
                            @endswitch
                        </strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div> --}}
    </main>

    <footer>
        Fecha de creación del PDF: {{ \Carbon\Carbon::now()->format('d-m-Y H:i')}}
    </footer>
</body>