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
    <img src="img/logo-pdf-cdp.png" alt="" style="height: 50px;">
</header>
<main>
    <div>
        <h1>
            {{$concurso->nombre}}
        </h1>
        <div>
            <h2>
                Datos Generales
            </h2>
            <div> 
                Nombre del Concurso <strong>{{$concurso->nombre}}</strong>
            </div>
            <div>
                Número <strong>#{{$concurso->numero}}</strong>
            </div>
            <div>
                Descripción <br> <strong>{{$concurso->descripcion}}</strong>
            </div>
            <div>
                Sedes <br>
                @foreach ($concurso->sedes as $sede)
                <strong>{{$sede->nombre}}</strong><br>
                @endforeach
            </div>
            <hr>
            <div>
                Fecha Inicio <strong>{{$concurso->fecha_inicio->format('d-m-Y H:i')}}</strong>
            </div>
            <div>
                Fecha Cierre <strong>{{$concurso->fecha_cierre->format('d-m-Y H:i')}}</strong>
            </div>
            <div>
                Prórrogas <br>
                @foreach ($concurso->prorrogas as $key => $prorroga)
                <strong>Prórroga {{ $key+1 }}:
                        {{ $prorroga->fecha_anterior->format('d-m-Y - H:i') }} 
                        <span style="margin: 0 10px 0 10px">></span> 
                        {{ $prorroga->fecha_actual->format('d-m-Y - H:i') }}
                </strong> <br>
                @endforeach
            </div>
            <div>
                Contactos <br>
                @foreach ($concurso->contactos as $contacto)
                <strong>{{ $contacto->tipo == 'administrativo' ? 'Administrativo' : 'Técnico' }}:
                    {{ $contacto->nombre }} - {{ $contacto->correo }} - {{ $contacto->telefono }}
                </strong> <br>
                @endforeach
            </div>
            <div>
                Rubro <strong>{{$concurso->subrubro->rubro->nombre}}</strong> <br>
                SubRubro <strong>{{$concurso->subrubro->nombre}}</strong>
            </div>
        </div>
        <div>
            <h2>
                Proveedores
            </h2>
            @if(count($concurso->invitaciones) == 0)
            <i>No existen proveedores asociados al concurso</i>
            @endif
            @foreach($concurso->invitaciones as $invitacion)
            <div>
                <div>
                    <div>
                        Razón Social: <strong>{{$invitacion->proveedor->razonsocial}}</strong>
                    </div>
                    <div>
                        CUIT: <strong>{{$invitacion->proveedor->cuit}}</strong>
                    </div>
                    <div>
                        Correo: <strong>{{$invitacion->proveedor->correo}}</strong>
                    </div>
                    <div>
                        Agregado al concurso: <strong>{{$invitacion->created_at->format('d-m-Y')}}</strong>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>
<footer>
    Fecha de creación del PDF: <strong>{{ \Carbon\Carbon::now()->format('d-m-Y H:i')}}</strong>
</footer>
</body>
</html>