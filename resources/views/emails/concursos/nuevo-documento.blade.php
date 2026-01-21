<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <title>Nueva Documentación en Concurso</title>
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
                            <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #1F2937;">Concurso de Precios #{{ $documento->concurso->numero }}</h2>
                            <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #1F2937;">"{{ $documento->concurso->nombre }}"</h2>
                            <hr>
                            
                            <p style="margin: 0; font-size: 16px; color: #4B5563;">
                                Estimado/a proveedor/a,
                            </p>
                            <p style="margin: 0; font-size: 16px; color: #4B5563;">
                                Se ha cargado nueva documentación en el Concurso de Precios de referencia
                                <br><br>
                                <strong>Documento cargado:</strong> {{ $documento->documentoTipo->nombre }}.
                                <br><br>
                                <strong>Fecha de carga:</strong> {{ $documento->created_at->format('d-m-Y H:i') }}.
                            </p>
                            <p style="margin: 10px 0 10px 0; font-size: 16px; color: #4B5563;">Para conocer más detalles acceda al siguiente enlace:</p>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 10px 0 30px 0;" align="center">
                            <!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://buenosairesenergia.com.ar/registroproveedores/concursos/{{ $documento->concurso->id }}" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="10%" stroke="f" fillcolor="#002E80">
                                <w:anchorlock/>
                                <center>
                            <![endif]-->
                            <a href="https://buenosairesenergia.com.ar/registroproveedores/concursos/{{ $documento->concurso->id }}" 
                               style="background-color: #002E80; border-radius: 4px; color: #ffffff; display: inline-block; font-size: 14px; font-weight: bold; line-height: 40px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none;">Link al Concurso</a>
                            <!--[if mso]>
                                </center>
                            </v:roundrect>
                            <![endif]-->
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

