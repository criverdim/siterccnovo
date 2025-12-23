<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Pagamento</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
            margin: 24px;
        }
        .card {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 20px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 4px;
            color: #111827;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 4px;
        }
        .col {
            flex: 1 1 50%;
            padding-right: 8px;
            box-sizing: border-box;
        }
        .label {
            font-size: 11px;
            color: #6b7280;
        }
        .value {
            font-size: 12px;
            color: #111827;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 11px;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-warning {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #6b7280;
        }
        .mt-2 {
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">Recibo de Pagamento</div>
        <div class="subtitle">
            Emitido em {{ now()->format('d/m/Y H:i') }}
        </div>

        <div class="section-title">Informações do Pagamento</div>
        <div class="row">
            <div class="col">
                <div class="label">Data da Operação</div>
                <div class="value">
                    {{ optional($p->created_at)->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="col">
                <div class="label">Método</div>
                <div class="value">{{ strtoupper((string) $p->payment_method) }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="label">Status</div>
                <div class="value">
                    @php
                        $status = (string) $p->payment_status;
                        $statusClass = 'badge-warning';
                        if ($status === 'approved') {
                            $statusClass = 'badge-success';
                        } elseif (in_array($status, ['rejected', 'cancelled', 'refunded'], true)) {
                            $statusClass = 'badge-danger';
                        }
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        {{ strtoupper($status) }}
                    </span>
                </div>
            </div>
            <div class="col">
                <div class="label">MP Payment ID</div>
                <div class="value">{{ $p->mp_payment_id ?? '-' }}</div>
            </div>
        </div>

        <div class="section-title mt-2">Evento</div>
        <div class="row">
            <div class="col">
                <div class="label">Nome do Evento</div>
                <div class="value">{{ optional($p->event)->name }}</div>
            </div>
            <div class="col">
                <div class="label">Data do Evento</div>
                <div class="value">
                    @if(optional($p->event)->start_date)
                        {{ optional($p->event->start_date)->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="label">Local</div>
                <div class="value">{{ optional($p->event)->location }}</div>
            </div>
            <div class="col">
                <div class="label">Valor</div>
                <div class="value">
                    @if(optional($p->event)->price !== null)
                        R$ {{ number_format((float) $p->event->price, 2, ',', '.') }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>

        <div class="section-title mt-2">Participante</div>
        <div class="row">
            <div class="col">
                <div class="label">Nome</div>
                <div class="value">{{ optional($p->user)->name }}</div>
            </div>
            <div class="col">
                <div class="label">E-mail</div>
                <div class="value">{{ optional($p->user)->email }}</div>
            </div>
        </div>

        <div class="footer">
            Este recibo confirma o registro do pagamento no sistema, baseado nas
            informações recebidas do provedor de pagamentos (Mercado Pago).
        </div>
    </div>
</body>
</html>

