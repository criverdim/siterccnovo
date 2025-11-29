<!doctype html>
<html>
<body style="font-family:Inter,Arial,sans-serif;">
    <div style="max-width:560px;margin:0 auto;padding:24px;">
        <h2 style="color:#0b7a48;margin:0 0 12px;">Redefinição de senha</h2>
        <p style="color:#333;">Recebemos uma solicitação para redefinir sua senha. Se você não foi quem solicitou, ignore este e-mail.</p>
        <p style="margin:16px 0;">
            <a href="{{ url('/password/reset/'.$token.'?email='.$email) }}" style="background:#059669;color:#fff;padding:12px 16px;border-radius:8px;text-decoration:none;">Redefinir senha</a>
        </p>
        <p style="color:#666;font-size:12px;">Este link expira em 60 minutos.</p>
    </div>
</body>
</html>
