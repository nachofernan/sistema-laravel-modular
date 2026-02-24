<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        /* Estilos base */
        body { 
            font-family: sans-serif; 
            font-size: 11px; /* Bajamos un poco el tamaño para que quepa todo bien con el membrete */
            color: #333; 
            margin-top: 80px; /* Margen superior para que el contenido no tape el membrete */
        }
        
        /* Configuración del Membrete */
        .membrete-container {
            position: fixed;
            top: -20px; /* Ajusta según necesites subir o bajar la imagen */
            left: 0px;
            right: 0px;
            text-align: center;
            height: 71px;
            width: 100%;
        }

        .membrete-img {
            width: 625px;
            height: 71px;
        }

        .header { 
            text-align: center; 
            border-bottom: 2px solid #ed1c24; 
            padding-bottom: 5px; 
            margin-bottom: 15px; 
        }

        /* Resto de estilos que ya tenías */
        .section-title { background-color: #f4f4f4; padding: 5px 10px; font-weight: bold; margin-top: 12px; border-left: 4px solid #ed1c24; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f9f9f9; }
        .label { font-weight: bold; width: 30%; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="membrete-container">
        {{-- Usamos path absoluto para que DomPDF encuentre la imagen en el sistema de archivos --}}
        <img src="{{ public_path('img/membrete.png') }}" class="membrete-img">
    </div>

    <div class="header">
        <h2 style="margin: 0;">ANEXO SOLICITUD DE PEDIDO (SAP)</h2>
    </div>

    <div class="section-title">Información General y Centrales</div>
    <table>
        <tr>
            <td class="label">Título:</td>
            <td>{{ $titulo }}</td>
        </tr>
        <tr>
            <td class="label">Centrales Seleccionadas:</td>
            <td>{{ implode(', ', $centrales) }}</td>
        </tr>
        <tr>
            <td class="label">Documentos Adjuntos:</td>
            <td>{{ count($documentos_adjuntos) > 0 ? implode(', ', $documentos_adjuntos) : 'Ninguno' }}</td>
        </tr>
    </table>

    <div class="section-title">Configuración Técnica</div>
    <table>
        <tr>
            <td class="label">Visita Técnica:</td><td>{{ $visita_tecnica }}</td>
        </tr>
        <tr>
            <td class="label">Garantía Técnica:</td><td>{{ $garantia_tecnica }} {{ $garantia_tecnica === 'Si' ? "($plazo_garantia)" : '' }}</td>
        </tr>
        <tr>
            <td class="label">Adjudicación Parcial:</td><td>{{ $adjudicacion_parcial }}</td>
        </tr>
        <tr>
            <td class="label">Marcas Alternativas:</td><td>{{ $marcas_alternativas }}</td>
        </tr>
        <tr>
            <td class="label">Ingreso a Planta:</td>
            <td>{{ $ingreso_planta }}
                {{ $ingreso_planta === 'Si' ? " - Adjuntar Anexo Requisitos de Ingreso a Planta" : '' }}
            </td>
        </tr>
        <tr>
            <td class="label">Sustancias Peligrosas:</td>
            <td>{{ $sustancias_peligrosas }}
                {{ $sustancias_peligrosas === 'Si' ? " - Adjuntar Anexo Adquisición y Transporte de Productos con Categoría Peligrosos" : '' }}
            </td>
        </tr>
    </table>

    <div class="section-title">Contactos Responsables</div>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Técnico</td>
                <td>{{ $contacto_tecnico_nombre }}</td>
                <td>{{ $contacto_tecnico_email }}</td>
                <td>{{ $contacto_tecnico_tel }}</td>
            </tr>
            <tr>
                <td>Seguimiento</td>
                <td>{{ $contacto_seguimiento_nombre }}</td>
                <td>{{ $contacto_seguimiento_email }}</td>
                <td>{{ $contacto_seguimiento_tel }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Logística y Entrega</div>
    <table>
        <tr><td class="label">Plazo:</td><td>{{ $plazo_entrega }}</td></tr>
        <tr><td class="label">A partir de:</td><td>{{ $a_partir_de }}</td></tr>
        <tr><td class="label">Lugar:</td><td>{{ $lugar_entrega }}</td></tr>
    </table>

    <div class="section-title">Proveedores Sugeridos</div>
    <table>
        <thead>
            <tr>
                <th>Razón Social</th>
                <th>CUIT</th>
                <th>Email</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proveedores_seleccionados as $prov)
            <tr>
                <td>
                    {{ $prov['razonsocial'] }}
                    {{ $prov['tipo'] == 'existente' ? '(ID: ' . $prov['id'] . ')' : '' }}
                </td>
                <td>{{ $prov['cuit'] }}</td>
                <td>{{ $prov['correo'] }}</td>
                <td>{{ $prov['telefono'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="section-title">Rubro y Subrubro</div>
    <table>
        <tr><td class="label">Rubro:</td><td>{{ $rubro_nombre }}</td></tr>
        <tr><td class="label">Subrubro:</td><td>{{ $subrubro_nombre }}</td></tr>
    </table>

    @if($observaciones)
    <div class="section-title">Observaciones</div>
    <div style="padding: 10px; border: 1px solid #ddd; margin-top: 5px;">
        {{ $observaciones }}
    </div>
    @endif

    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }} - Sistema de Gestión de Proveedores - Por {{ Auth::user()->realname }} ({{ Auth::user()->legajo }})
    </div>
</body>
</html>
</html>