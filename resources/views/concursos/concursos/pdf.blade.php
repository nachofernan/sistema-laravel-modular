<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Concurso #{{ $concurso->numero }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; margin-top: 80px; }
        .membrete-container { position: fixed; top: -20px; left: 0; right: 0; text-align: center; height: 71px; }
        .membrete-img { width: 625px; height: 71px; }
        
        .header { text-align: center; border-bottom: 2px solid #ed1c24; padding-bottom: 5px; margin-bottom: 15px; }
        .section-title { background-color: #f4f4f4; padding: 5px 10px; font-weight: bold; margin-top: 12px; border-left: 4px solid #ed1c24; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f9f9f9; font-weight: bold; }
        .label { font-weight: bold; width: 30%; background-color: #fcfcfc; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }
        .salto-pagina { page-break-before: always; }
    </style>
</head>
<body>
    <div class="membrete-container">
        <img src="{{ public_path('img/membrete.png') }}" class="membrete-img">
    </div>

    <div class="header">
        <h2 style="margin: 0;">RESUMEN DE CONCURSO DE PRECIOS</h2>
    </div>

    <div class="section-title">Datos Generales</div>
    <table>
        <tr><td class="label">Nombre del Concurso:</td><td>{{ $concurso->nombre }}</td></tr>
        <tr><td class="label">Número:</td><td>#{{ $concurso->numero }}</td></tr>
        <tr><td class="label">Descripción:</td><td>{{ $concurso->descripcion }}</td></tr>
        <tr>
            <td class="label">Sedes:</td>
            <td>{{ $concurso->sedes->pluck('nombre')->implode(', ') }}</td>
        </tr>
    </table>

    <div class="section-title">Vigencia y Período</div>
    <table>
        <tr><td class="label">Inicio:</td><td>{{ $concurso->fecha_inicio->format('d/m/Y H:i') }} hs</td></tr>
        <tr><td class="label">Cierre:</td><td>{{ $concurso->fecha_cierre->format('d/m/Y H:i') }} hs</td></tr>
    </table>
    @if($concurso->prorrogas->count() > 0)
    <table>
        @foreach($concurso->prorrogas as $prorroga)
        <tr>
            <td class="label">Prórroga {{ $loop->iteration }}:</td>
            <td>Fecha anterior: {{ $prorroga->fecha_anterior->format('d/m/Y H:i') }} hs - Nueva fecha: {{ $prorroga->fecha_actual->format('d/m/Y H:i') }} hs</td>
        </tr>
        @endforeach
    </table>
    @endif

    <div class="section-title">Proveedores Invitados ({{ count($concurso->invitaciones) }})</div>
    <table>
        <thead>
            <tr>
                <th>Razón Social</th>
                <th>CUIT</th>
                <th>Fecha Invitación</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($concurso->invitaciones as $inv)
            <tr>
                <td>{{ $inv->proveedor->razonsocial }}</td>
                <td>{{ $inv->proveedor->cuit }}</td>
                <td>{{ $inv->created_at->format('d/m/Y') }}</td>
                <td>
                    @switch($inv->intencion)
                        @case(0) Invitado @break
                        @case(1) Con intención @break
                        @case(2) No participará @break
                        @case(3) Presentó oferta @break
                    @endswitch
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }} - Sistema de Gestión de Proveedores
    </div>
</body>
</html>
</body>
</html>