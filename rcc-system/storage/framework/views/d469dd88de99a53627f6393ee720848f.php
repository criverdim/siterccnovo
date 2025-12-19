<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Erro interno</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html,body{height:100%;margin:0}
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#f9fafb;color:#111827;display:flex;align-items:center;justify-content:center}
        .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.06);padding:28px;max-width:640px;width:94%}
        .title{font-size:20px;font-weight:600;color:#0f766e;margin:0 0 8px}
        .desc{font-size:14px;color:#374151;margin:0 0 16px}
        .hint{font-size:12px;color:#6b7280;margin-top:10px}
        .actions{display:flex;gap:10px;margin-top:16px}
        .btn{display:inline-block;padding:10px 14px;border-radius:8px;border:1px solid #d1fae5;background:#ecfdf5;color:#065f46;text-decoration:none}
        .btn.secondary{background:#f3f4f6;border-color:#e5e7eb;color:#374151}
    </style>
    </head>
<body>
<div class="card">
    <h1 class="title">Ocorreu um erro ao entrar no painel</h1>
    <p class="desc">Nosso sistema registrou o problema para análise. Tente novamente em instantes ou volte para a página inicial.</p>
    <div class="actions">
        <a class="btn" href="/login?area=admin&redirect=%2Fadmin">Tentar novamente</a>
        <a class="btn secondary" href="/">Página inicial</a>
    </div>
    <p class="hint">Se o erro persistir, contate o suporte e informe a data e o horário do ocorrido.</p>
</div>
</body>
</html>
<?php /**PATH /var/www/html/rcc-system/resources/views/errors/500.blade.php ENDPATH**/ ?>