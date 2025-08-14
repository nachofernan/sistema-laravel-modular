<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test SMTP - Buenos Aires Energía</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .status {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .success-icon {
            font-size: 48px;
            color: #28a745;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">⚡ Buenos Aires Energía</div>
            <h1>Test de Configuración SMTP</h1>
        </div>

        <div class="status">
            <div class="success-icon">✅</div>
            <h2>¡Email Enviado Exitosamente!</h2>
            <p>La configuración SMTP está funcionando correctamente.</p>
        </div>

        <div class="info-box">
            <h3>📊 Información del Envío</h3>
            <p><span class="info-label">Destinatario:</span> {{ $destinatario }}</p>
            <p><span class="info-label">Mailer:</span> {{ $mailer }}</p>
            <p><span class="info-label">Host SMTP:</span> {{ $host }}</p>
            <p><span class="info-label">Fecha y Hora:</span> {{ $timestamp }}</p>
        </div>

        <div class="info-box">
            <h3>🔧 Configuración Actual</h3>
            <p>Este email fue enviado usando la configuración SMTP de Office 365 configurada en tu aplicación Laravel.</p>
            <p>Si recibes este email, significa que:</p>
            <ul>
                <li>✅ Las credenciales SMTP son correctas</li>
                <li>✅ La conexión al servidor funciona</li>
                <li>✅ La autenticación es exitosa</li>
                <li>✅ El envío de emails está operativo</li>
            </ul>
        </div>

        <div class="info-box">
            <h3>🚀 Próximos Pasos</h3>
            <p>Ahora puedes:</p>
            <ul>
                <li>Cambiar <code>MAIL_MAILER=smtp</code> en tu archivo .env</li>
                <li>Probar el envío de emails desde tu aplicación</li>
                <li>Verificar que todos los módulos funcionen correctamente</li>
                <li>Monitorear los logs para detectar cualquier problema</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>Buenos Aires Energía</strong></p>
            <p>Plataforma de Desarrollo - Test SMTP</p>
            <p>Este es un email de prueba automático. No responder.</p>
        </div>
    </div>
</body>
</html> 