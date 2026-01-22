</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <title>BAJA - CONCURSO DE PRECIOS N° {{ $concurso->numero }}</title>
    <!--[if mso]>
    <style type="text/css">
        table {border-collapse: collapse; border-spacing: 0; margin: 0;}
        div, td {padding: 0;}
        div {margin: 0 !important;}
    </style>
    <noscript>
    <xml>
        <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; min-width: 100%; background-color: #f4f4f4;">
    <!--[if mso]>
    <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center">
    <![endif]-->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #dcdcdc; font-family: Arial, sans-serif;">
        <!-- Header -->
        <tr>
            <td bgcolor="#002E80" align="center" style="padding: 20px 10px;">
                <img src="http://buenosairesenergia.com.ar/img/logo_50b.png" alt="BAESA Logo" width="140" height="50" style="display: block; border: 0;">
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">

                    <!-- Proveedor Info -->
                    <tr>
                        <td style="padding: 0 0 20px 0;">
                            <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #1F2937;">Concurso de Precios #{{ $concurso->numero }}</h2>
                            <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #1F2937;">"{{ $concurso->nombre }}"</h2>
                            <hr>
                            
                            <p style="margin: 0; font-size: 16px; color: #4B5563;">
                                Estimado/a proveedor/a,
                            </p>
                            <p style="margin: 0; font-size: 16px; color: #4B5563;">
                                Por medio de la presente, se informa que el <strong>Concurso de Precios de referencia ha sido dado de baja</strong>.
                                <br><br>
                                Para cualquier aclaración adicional, podrán comunicarse con el <strong>Contacto Administrativo del Concurso de Precios</strong>.
                                <br>
                                @foreach ($concurso->contactos as $contacto)
                                    @if ($contacto->tipo === 'administrativo')
                                        {{ $contacto->nombre }} - {{ $contacto->correo }} - {{ $contacto->telefono }}
                                    @endif
                                @endforeach
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 20px 0;">
                            <p style="margin: 0; font-size: 16px; color: #4B5563;">Lamentamos las molestias que esta situación pudiera ocasionar y quedamos a disposición para futuros procesos licitatorios.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td bgcolor="#F3F4F6" style="padding: 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="center" style="padding: 0 0 10px 0;">
                            <p style="margin: 0; font-size: 12px; color: #6B7280;">Este es un mensaje automático, por favor no responda a este correo.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <p style="margin: 0; font-size: 12px; color: #6B7280;">&copy; {{now()->format('Y')}} BAESA. Todos los derechos reservados.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--[if mso]>
    </td></tr></table>
    <![endif]-->
</body>
</html>

