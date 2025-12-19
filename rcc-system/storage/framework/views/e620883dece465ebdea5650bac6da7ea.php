<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso negado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html,body{height:100%;margin:0}
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f9fafb;color:#111827;display:flex;align-items:center;justify-content:center}
        .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.06);padding:28px;max-width:640px;width:94%}
        .title{font-size:20px;font-weight:600;color:#b91c1c;margin:0 0 8px}
        .desc{font-size:14px;color:#374151;margin:0 0 16px}
        .hint{font-size:12px;color:#6b7280;margin-top:10px}
        .actions{display:flex;gap:10px;margin-top:16px}
        .btn{display:inline-block;padding:10px 14px;border-radius:8px;border:1px solid #fecaca;background:#fee2e2;color:#991b1b;text-decoration:none}
        .btn.secondary{background:#f3f4f6;border-color:#e5e7eb;color:#374151}
    </style>
    </head>
<body>
<div class="card">
    <h1 class="title">Acesso ao painel negado</h1>
    <p class="desc">Sua conta não possui autorização para acessar o painel administrativo.</p>
    <div class="actions">
        <a class="btn" href="/login?area=admin&redirect=%2Fadmin">Trocar de conta</a>
        <a class="btn secondary" href="/">Página inicial</a>
    </div>
    <p class="hint">Se você acredita que isso é um engano, contate o suporte e informe a data e horário.</p>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/rcc-system/resources/views/errors/403.blade.php ENDPATH**/ ?>