<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresso - {{ $ticket->event->name }}</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8fafc;
        }
        
        .ticket-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .ticket-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 20px 20px;
        }
        
        .event-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .ticket-type {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .ticket-body {
            padding: 40px 30px;
            display: flex;
            gap: 40px;
        }
        
        .ticket-info {
            flex: 1;
        }
        
        .ticket-qr {
            width: 200px;
            text-align: center;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 500;
        }
        
        .ticket-code {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        
        .qr-code {
            width: 180px;
            height: 180px;
            margin: 0 auto 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .validation-info {
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }
        
        .ticket-footer {
            background: #f1f5f9;
            padding: 20px 30px;
            border-top: 2px dashed #cbd5e1;
            text-align: center;
        }
        
        .footer-text {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .organizer-info {
            font-size: 11px;
            color: #475569;
        }
        
        .event-date {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 20px;
        }
        
        .date-main {
            font-size: 18px;
            font-weight: bold;
            color: #92400e;
        }
        
        .date-sub {
            font-size: 14px;
            color: #b45309;
            margin-top: 5px;
        }
        
        .location-box {
            background: #e0f2fe;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #0284c7;
        }
        
        .location-main {
            font-size: 16px;
            font-weight: 600;
            color: #0c4a6e;
        }
        
        .location-sub {
            font-size: 14px;
            color: #075985;
            margin-top: 5px;
        }
        
        .buyer-info {
            background: #f0fdf4;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #16a34a;
            margin-bottom: 20px;
        }
        
        .buyer-name {
            font-size: 16px;
            font-weight: 600;
            color: #14532d;
        }
        
        .buyer-email {
            font-size: 14px;
            color: #166534;
            margin-top: 5px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        
        .divider {
            border-top: 2px dashed #cbd5e1;
            margin: 30px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -2px;
            width: 10px;
            height: 10px;
            background: #cbd5e1;
            border-radius: 50%;
        }
        
        .divider::after {
            content: '';
            position: absolute;
            top: -5px;
            right: -2px;
            width: 10px;
            height: 10px;
            background: #cbd5e1;
            border-radius: 50%;
        }
        
        @media print {
            .ticket-container {
                box-shadow: none;
            }
            
            body {
                background: white;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Cabeçalho -->
        <div class="ticket-header">
            <div class="event-title">{{ $ticket->event->name }}</div>
            <div class="ticket-type">Ingresso Digital</div>
        </div>
        
        <!-- Corpo do Ingresso -->
        <div class="ticket-body">
            <!-- Informações do Ingresso -->
            <div class="ticket-info">
                <!-- Código do Ingresso -->
                <div class="info-section">
                    <div class="info-label">Código do Ingresso</div>
                    <div class="ticket-code">{{ $ticket->ticket_code }}</div>
                </div>
                
                <!-- Data do Evento -->
                <div class="event-date">
                    <div class="date-main">
                        {{ \Carbon\Carbon::parse($ticket->event->start_date)->format('d/m/Y') }}
                    </div>
                    <div class="date-sub">
                        {{ \Carbon\Carbon::parse($ticket->event->start_date)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($ticket->event->end_date)->format('H:i') }}
                    </div>
                </div>
                
                <!-- Local do Evento -->
                <div class="location-box">
                    <div class="location-main">{{ $ticket->event->location }}</div>
                    @if(isset($ticket->event->address))
                        <div class="location-sub">{{ $ticket->event->address }}</div>
                    @endif
                </div>
                
                <!-- Comprador -->
                <div class="buyer-info">
                    <div class="buyer-name">{{ $ticket->user->name }}</div>
                    <div class="buyer-email">{{ $ticket->user->email }}</div>
                </div>
                
                <!-- Informações Adicionais -->
                <div class="info-section">
                    <div class="info-label">Valor Pago</div>
                    <div class="info-value">R$ {{ number_format($ticket->payment->amount, 2, ',', '.') }}</div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">Data da Compra</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($ticket->purchase_date)->format('d/m/Y H:i') }}
                    </div>
                </div>
                
                <div class="info-section">
                    <div class="info-label">Status</div>
                    <div>
                        <span class="status-badge status-active">
                            {{ $ticket->status === 'active' ? 'Válido' : 'Utilizado' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- QR Code -->
            <div class="ticket-qr">
                <div class="info-section">
                    <div class="info-label">QR Code para Check-in</div>
                </div>
                
                @if($ticket->qr_code)
                    <div class="qr-code">
                        <img src="data:image/png;base64,{{ $ticket->qr_code }}" alt="QR Code">
                    </div>
                @else
                    <div class="qr-code" style="display: flex; align-items: center; justify-content: center; background: #f8fafc;">
                        <div style="text-align: center; color: #64748b;">
                            <div style="font-size: 48px; margin-bottom: 10px;">📱</div>
                            <div style="font-size: 12px;">QR Code será gerado em breve</div>
                        </div>
                    </div>
                @endif
                
                <div class="validation-info">
                    <strong>Instruções:</strong><br>
                    Apresente este ingresso digital na entrada do evento. 
                    O QR Code deve ser lido pelo organizador para validação.
                </div>
            </div>
        </div>
        
        <!-- Divisória -->
        <div class="divider"></div>
        
        <!-- Rodapé -->
        <div class="ticket-footer">
            <div class="footer-text">
                Este ingresso é válido apenas para uma entrada no evento.
            </div>
            <div class="footer-text">
                Em caso de dúvidas, entre em contato com o organizador do evento.
            </div>
            @if(isset($ticket->event->organizers) && count($ticket->event->organizers) > 0)
                <div class="organizer-info">
                    Organizado por: {{ implode(', ', $ticket->event->organizers) }}
                </div>
            @endif
            <div class="organizer-info" style="margin-top: 10px;">
                Emitido em: {{ now()->format('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>
