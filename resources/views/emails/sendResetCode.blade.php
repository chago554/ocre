<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu identidad</title>
    <style>
        /* Estilos generales para asegurar compatibilidad */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f9fafb;
            padding-bottom: 40px;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #E6AD56;
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
            color: #374151;
            line-height: 1.6;
            text-align: center;
        }
        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .code-box {
            display: inline-block;
            background-color: #f3f4f6;
            padding: 15px 30px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #111827;
            border: 1px dashed #d1d5db;
            margin: 20px 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .notice {
            font-size: 14px;
            color: #9ca3af;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Verificación de Seguridad</h1>
            </div>

            <div class="content">
                <p>¡Hola {{ $data['name'] }}!</p>
                <p>Has solicitado restablecer tu contraseña. Utiliza el siguiente código de verificación para continuar con el proceso:</p>
                
                <div class="code-box">
                    {{ $data['code'] }}
                </div>

                <p class="notice">
                    Este código expirará pronto. Si no has solicitado este cambio, puedes ignorar este mensaje de forma segura; tu cuenta sigue protegida.
                </p>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. Todos los derechos reservados.</p>
                <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
            </div>
        </div>
    </div>
</body>
</html>