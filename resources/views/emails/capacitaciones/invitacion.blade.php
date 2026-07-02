<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invitación a Capacitación</title>
    <!--[if mso]>
    <style type="text/css">
        table {border-collapse: collapse; border-spacing: 0; margin: 0;}
        div, td {padding: 0;}
        div {margin: 0 !important;}
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; min-width: 100%; background-color: #f4f4f4;">
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
                    <!-- Alert Box -->
                    <tr>
                        <td style="padding: 0 0 20px 0;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#EEF2FF" style="border: 1px solid #a5b4fc; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <h2 style="margin: 0; font-size: 16px; color: #4338CA; text-align: center;">Nueva invitación a capacitación</h2>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Info -->
                    <tr>
                        <td style="padding: 0 0 10px 0;">
                            <p style="margin: 0 0 8px 0; font-size: 15px; color: #1F2937;">
                                Hola <strong>{{ $invitacion->usuario->realname }}</strong>, fuiste invitado/a a la siguiente capacitación:
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 20px 0;">
                            <h2 style="margin: 0 0 8px 0; font-size: 18px; color: #1F2937;">{{ $invitacion->capacitacion->nombre }}</h2>
                            <p style="margin: 0 0 4px 0; font-size: 14px; color: #6B7280;">
                                Inicio: <strong>{{ $invitacion->capacitacion->fecha_inicio->format('d/m/Y') }}</strong>
                                &nbsp;—&nbsp;
                                Cierre: <strong>{{ $invitacion->capacitacion->fecha_final->format('d/m/Y') }}</strong>
                            </p>
                            <p style="margin: 0; font-size: 14px; color: #6B7280;">
                                Modalidad: <strong>{{ ucfirst($invitacion->tipo) }}</strong>
                            </p>
                        </td>
                    </tr>

                    @if($invitacion->capacitacion->descripcion)
                    <tr>
                        <td style="padding: 0 0 20px 0;">
                            <p style="margin: 0; font-size: 14px; color: #374151;">{{ $invitacion->capacitacion->descripcion }}</p>
                        </td>
                    </tr>
                    @endif

                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 10px 0 30px 0;" align="center">
                            <a href="{{ route('home.capacitacions.show', $invitacion->capacitacion) }}"
                               style="background-color: #002E80; border-radius: 4px; color: #ffffff; display: inline-block; font-size: 14px; font-weight: bold; line-height: 40px; text-align: center; text-decoration: none; width: 220px; -webkit-text-size-adjust: none;">Ver Capacitación</a>
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
                            <p style="margin: 0; font-size: 12px; color: #6B7280;">&copy; {{ now()->format('Y') }} BAESA. Todos los derechos reservados.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
