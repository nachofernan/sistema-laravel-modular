<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proveedor: {{ $proveedor->razonsocial }}</title>
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
        .doc-vencido { color: #ed1c24; font-weight: bold; font-size: 8px; border: 1px solid #ed1c24; padding: 1px 3px; border-radius: 3px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="membrete-container">
        <img src="{{ public_path('img/membrete.png') }}" class="membrete-img">
    </div>

    <div class="header">
        <h2 style="margin: 0;">FICHA REGISTRO DE PROVEEDOR (ID: {{ $proveedor->id }})</h2>
    </div>

    <div class="section-title">Información Institucional</div>
    <table>
        <tr>
            <td class="label">Razón Social:</td>
            <td style="font-size: 13px; font-weight: bold;">{{ $proveedor->razonsocial }}</td>
        </tr>
        <tr><td class="label">Nombre Fantasía:</td><td>{{ $proveedor->fantasia ?: '-' }}</td></tr>
        <tr><td class="label">CUIT:</td><td>{{ $proveedor->cuit }}</td></tr>
        <tr><td class="label">Correo:</td><td>{{ $proveedor->correo }}</td></tr>
        <tr><td class="label">Teléfono:</td><td>{{ $proveedor->telefono }}</td></tr>
        <tr><td class="label">Estado:</td><td>Nivel {{ $proveedor->estado_id }}</td></tr>
    </table>

    <div class="section-title">Documentación Presentada</div>
    <table>
        <thead>
            <tr>
                <th>Documento / Tipo</th>
                <th>Cargado</th>
                <th>Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            @php
                $docTypeId = 0;
            @endphp
            @foreach($proveedor->documentos as $doc)
            @if($doc->documentoTipo->id != $docTypeId)
                @php
                    $docTypeId = $doc->documentoTipo->id;
                @endphp
            @else
                @php
                    continue;
                @endphp
            @endif
            <tr>
                <td>{{ $doc->documentoTipo->nombre }}</td>
                <td>{{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y') }}</td>
                <td>
                    {{ $doc->vencimiento ? \Carbon\Carbon::parse($doc->vencimiento)->format('d/m/Y') : 'Sin vencer' }}
                    @if($doc->vencimiento && \Carbon\Carbon::parse($doc->vencimiento)->isPast())
                        <span class="doc-vencido">Vencido</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Datos de Contacto y Ubicación</div>
    <table>
        @foreach($proveedor->direcciones as $dir)
        <tr>
            <td class="label">{{ $dir->tipo }}:</td>
            <td>{{ $dir->calle }} #{{ $dir->altura }}, {{ $dir->ciudad }} ({{ $dir->provincia }})</td>
        </tr>
        @endforeach
        @foreach ($proveedor->contactos as $contacto)
            <tr>
                <td class="label">{{ $contacto->nombre }}</td>
                <td>{{ $contacto->telefono }} | {{ $contacto->correo }}</td>
            </tr>
        @endforeach
    </table>

    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }} - Registro de Proveedores - BAESA
    </div>
</body>
</html>