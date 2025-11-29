<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seu ingresso</title>
    <style>
        body { font-family: Inter, Arial, sans-serif; color:#111; background:#f9fafb; }
        .container { max-width: 640px; margin: 0 auto; padding: 24px; }
        .card { background:#fff; border:1px solid #eee; border-radius: 12px; padding: 20px; }
        .title { color:#0b7a48; font-weight: 700; font-size: 18px; }
        .muted { color:#6b7280; font-size:14px; }
        .cta { display:inline-block; margin-top:16px; padding:10px 14px; background:#c9a043; color:#fff; border-radius:10px; text-decoration:none; }
    </style>
    <!--[if mso]>
    <style>
      .card { border: 1px solid #eee; }
    </style>
    <![endif]-->
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="title">Ingresso gerado</div>
            <p>Olá, {{ $participation->user?->name }}.</p>
            <p>Seu ingresso para <strong>{{ $participation->event?->name }}</strong> foi gerado e está em anexo.</p>
            <p class="muted">Data: {{ optional($participation->event)->start_date?->format('d/m/Y') }} • Horário: {{ optional($participation->event)->start_time }}</p>
            <a class="cta" href="{{ url('/area/membro') }}" target="_blank" rel="noopener">Acessar Área do Usuário</a>
            <p class="muted" style="margin-top:16px;">Deus abençoe!</p>
        </div>
    </div>
    </body>
    </html>
