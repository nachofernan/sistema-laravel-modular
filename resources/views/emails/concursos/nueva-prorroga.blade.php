<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <title>Prórroga del Concurso</title>
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
                            <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #1F2937;">Concurso de Precios #{{ $prorroga->concurso->numero }}</h2>
                            <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #1F2937;">"{{ $prorroga->concurso->nombre }}"</h2>
                            <hr>
                            
                            <p style="margin: 0; font-size: 16px; color: #4B5563;">
                                Estimado/a <strong>{{ $nombre }}</strong>{{ $cuit ? " (CUIT: $cuit)" : "" }},
                            </p>
                            <p style="margin: 0; font-size: 16px; color: #4B5563;">
                                @if($tipo === 'interno')
                                    Se informa que se ha prorrogado la fecha de cierre del concurso de referencia.
                                @else
                                    Se informa que se prorrogó la fecha de apertura del Concurso de Precios de referencia.
                                @endif
                                <br><br>
                                <strong>Descripción del Concurso:</strong> {{ $concurso->descripcion }}.
                                <br><br>
                                <strong>NUEVA Fecha de Cierre:</strong> {{ $concurso->fecha_cierre->format('d-m-Y H:i') }}.
                            </p>
                            @if($tipo != 'interno')
                            <p style="margin: 10px 0 10px 0; font-size: 16px; color: #4B5563;">Solicitamos tengan a bien confirmar sus intenciones de participación, ingresando al Link de acceso:</p>
                            @endif
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 10px 0 30px 0;" align="center">
                            <!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $linkConcurso }}" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="10%" stroke="f" fillcolor="#002E80">
                                <w:anchorlock/>
                                <center>
                            <![endif]-->
                            <a href="{{ $linkConcurso }}" 
                            style="background-color: #002E80; border-radius: 4px; color: #ffffff; display: inline-block; font-size: 14px; font-weight: bold; line-height: 40px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none;">Link al Concurso</a>
                            <!--[if mso]>
                                </center>
                            </v:roundrect>
                            <![endif]-->
                        </td>
                    </tr>
                    @if($tipo != 'interno')
                    <tr>
                        <td style="padding: 0 0 20px 0;">
                            <p style="margin: 0; font-size: 16px; color: #4B5563;">Agradecemos su interés y esperamos contar con su participación.</p>
                        </td>
                    </tr>
                    @endif
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