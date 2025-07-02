<head>
    <style>
        @page {
            margin: 0;
            font-family: Helvetica, Arial, sans-serif;
        }

        body {
            margin: 3cm 2cm 2cm;
            color: #2d3748;
            line-height: 1.6;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1a264e;
            color: white;
            text-align: center;
            padding: 20px;
        }

        header img {
            height: 50px;
            margin-bottom: 10px;
        }

        main {
            background: white;
        }

        h1 {
            color: #1a264e;
            font-size: 24px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1a264e;
        }

        h2 {
            color: #2d4373;
            font-size: 20px;
            margin-top: 30px;
            padding: 10px;
            background: #f7fafc;
            border-left: 4px solid #1a264e;
        }

        .seccion {
            padding: 15px;
            background: #ffffff;
            border-radius: 5px;
        }

        .seccion:last-child {
            page-break-before: always;
        }

        .campo {
            margin: 5px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .campo:last-child {
            border-bottom: none;
        }

        .etiqueta {
            color: #4a5568;
            font-size: 0.9em;
        }

        strong {
            color: #2d3748;
            font-family: Helvetica, Arial, sans-serif;
        }

        .proveedor-card {
            background: #f8fafc;
            padding: 0 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 3px solid #1a264e;
            page-break-inside: avoid;
        }

        .prorroga-item {
            background: #edf2f7;
            padding: 8px 12px;
            margin: 5px 0;
            border-radius: 4px;
        }

        .contacto-item {
            display: block;
            padding: 8px 0;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2cm;
            background: #1a264e;
            color: white;
            text-align: center;
            line-height: 2cm;
            font-size: 0.9em;
        }

        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
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
            {{-- 
            <div class="campo">
                <span class="etiqueta">Estado:</span><br>
                <strong>
                    @if ($concurso->estado->id == 2 && $concurso->fecha_cierre < now())
                        Cerrado
                    @else
                        {{$concurso->estado->nombre}}
                    @endif
                </strong>
            </div> --}}

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

        <div class="seccion">
            <h2>Proveedores Invitados ({{count($concurso->invitaciones)}})</h2>
            @if(count($concurso->invitaciones) == 0)
            <i>No existen proveedores asociados al concurso</i>
            @endif
            
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
    </main>

    <footer>
        Fecha de creación del PDF: {{ \Carbon\Carbon::now()->format('d-m-Y H:i')}}
    </footer>
</body>