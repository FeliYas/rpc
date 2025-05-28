<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Nuevo mensaje de contacto</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto;">
        <tr>
            <td
                style="background-color: #ffffff; padding: 20px 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                <h1 style="color: #0D8141; text-align: center; font-family: Arial, Helvetica, sans-serif;">Nuevo mensaje
                    de contacto</h1>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">
                    <span style="font-weight: 600; color: #0D8141;">Nombre:</span> {{ $datos['nombre'] }}
                    {{ $datos['apellido'] }}
                </p>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">
                    <span style="font-weight: 600; color: #0D8141;">Teléfono:</span> {{ $datos['telefono'] }}
                </p>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">
                    <span style="font-weight: 600; color: #0D8141;">Empresa:</span>
                    {{ $datos['empresa'] ?? 'No especificada' }}
                </p>

                <h2 style="color: #0D8141; font-family: Arial, Helvetica, sans-serif;">Mensaje:</h2>

                <p style="margin: 8px 0; font-family: Arial, Helvetica, sans-serif;">{{ $datos['mensaje'] }}</p>

                <div style="text-align: center; margin-top: 30px;">
                    <img src="http://127.0.0.1:8000/storage/images/logologin.png" alt="Logo"
                        style="max-width: 150px; height: auto;">
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
