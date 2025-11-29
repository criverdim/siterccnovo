<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ingresso</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111; }
        .card { border: 2px solid #c9a043; border-radius: 12px; padding: 16px; }
        .title { font-size: 20px; color: #0b7a48; font-weight: bold; }
        .row { display: flex; gap: 16px; }
        .col { flex: 1; }
        .badge { display:inline-block; padding:4px 8px; border-radius:8px; background:#eee; }
        img { max-width: 240px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">Ingresso - {{ $participation->event?->name }}</div>
        <div class="row" style="margin-top: 10px;">
            <div class="col">
                <div><strong>Nome:</strong> {{ $participation->user?->name }}</div>
                <div><strong>Evento:</strong> {{ $participation->event?->name }}</div>
                <div><strong>Data:</strong> {{ optional($participation->event)->start_date?->format('d/m/Y') }}</div>
                <div><strong>Horário:</strong> {{ optional($participation->event)->start_time }}</div>
                <div><strong>UUID:</strong> {{ $participation->ticket_uuid }}</div>
                <div class="badge">Status: {{ $participation->payment_status }}</div>
            </div>
            <div class="col" style="text-align: right;">
                @if(isset($qrPath) && file_exists($qrPath))
                    <img src="{{ $qrPath }}" alt="QR Code" />
                @endif
            </div>
        </div>
    </div>
</body>
</html>
