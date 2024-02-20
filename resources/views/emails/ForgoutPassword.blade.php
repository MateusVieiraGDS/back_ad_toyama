<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;background-color: #f5f5f5;margin: 0;padding: 0;display: flex;align-items: flex-start;justify-content: center;">

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 100%;margin: 2em;padding: 20px;background-color: #ffffff;box-shadow: 1px 1px 8px rgb(0 0 0 / 33%);">

        <!-- Header -->
        <tbody><tr>
            <td align="center" style="padding: 10px 0;">
                <h2 style="color: #333;">Recuperação de Senha</h2>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 20px; text-align: center;font-size: 16px;">
                <p style="color: #555;text-transform: capitalize;">Olá <strong style="color: #ac2f10;">{{$username}}</strong>,</p>
                <p style="color: #555;">Recebemos uma solicitação para redefinir a senha da sua conta. Utilize o código abaixo para concluir o processo:</p>

                <!-- Highlighted Code with Rectangles -->
                <div style="text-align: center;">
                    <div style="background-color: #ac2f10; padding: 10px; margin: 5px; border-radius: 5px; color: #ffffff; font-family: 'Consolas', monospace; font-size: 32px;display: inline-block;">{{$code}}</div>
                </div>

                <p style="color: #555;">Este código expirará em 20 minutos. Se você não solicitou a recuperação de senha, ignore este e-mail.</p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td align="center" style="padding: 10px 0;">
                <p style="color: #888;">Obrigado,<br>                
                Suporte | AD Toyama</p>
                <p style="color: #888;">Este é um email automático, favor não responder.<br>
            </td>
        </tr>

    </tbody></table>



</body></html>