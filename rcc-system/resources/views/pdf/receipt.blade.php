<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Recibo</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .box { border: 1px solid #ccc; padding: 12px; border-radius: 8px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 8px; }
        .row { margin: 4px 0; }
    </style>
</head>
<body>
    <div class="box">
        <div class="title">Recibo de Pagamento</div>
        <div class="row">ID: {{ $p->id }}</div>
        <div class="row">Usuário: {{ optional($p->user)->name }}</div>
        <div class="row">Evento: {{ optional($p->event)->name }}</div>
        <div class="row">Status: {{ $p->payment_status }}</div>
        <div class="row">Método: {{ $p->payment_method }}</div>
        <div class="row">Data: {{ $p->created_at->format('d/m/Y H:i') }}</div>
        <div class="row" style="margin-top:10px;">
            Código QR:
            <div>
                {!! QrCode::size(128)->generate(url('/checkout').'?payment='.$p->mp_payment_id) !!}
            </div>
        </div>
    </div>
</body>
</html>
