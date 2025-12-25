<x-filament-panels::page>
    @php
        $stats = $this->getEventStats();
        $paymentDistribution = $this->getPaymentDistribution();
    @endphp

    <style>
        .event-details-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .event-header {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .event-title-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .event-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            line-height: 1.2;
        }

        .event-subtitle {
            font-size: 1.125rem;
            color: #64748b;
            margin-top: 0.5rem;
        }

        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            font-size: 0.95rem;
        }

        .meta-item svg {
            width: 18px;
            height: 18px;
            color: #6b7280;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .stat-progress {
            background: #f3f4f6;
            border-radius: 9999px;
            height: 8px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .stat-progress-bar {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.3s ease;
        }

        .stat-success { color: #10b981; }
        .stat-warning { color: #f59e0b; }
        .stat-info { color: #3b82f6; }
        .stat-danger { color: #ef4444; }

        .stat-success .stat-progress-bar { background: #10b981; }
        .stat-warning .stat-progress-bar { background: #f59e0b; }
        .stat-info .stat-progress-bar { background: #3b82f6; }
        .stat-danger .stat-progress-bar { background: #ef4444; }

        .payment-distribution {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payment-methods {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .payment-method {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            flex: 1;
            min-width: 150px;
            text-align: center;
        }

        .payment-method-name {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .payment-method-count {
            font-size: 1.5rem;
            font-weight: 700;
            color: #10b981;
        }

        .payment-method-total {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .participants-section {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .participants-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn-back {
            background: #6b7280;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .btn-edit {
            background: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-edit:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .event-title-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .event-title {
                font-size: 1.5rem;
            }

            .event-meta {
                flex-direction: column;
                gap: 0.75rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .payment-methods {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>

    <div class="event-details-container">
        <!-- Header do Evento -->
        <div class="event-header">
            <div class="event-title-section">
                <div>
                    <h1 class="event-title">{{ $event->name }}</h1>
                    @if($event->short_description)
                        <p class="event-subtitle">{{ $event->short_description }}</p>
                    @endif
                </div>
                <div class="action-buttons">
                    <a href="{{ route('filament.admin.resources.events.index') }}" class="btn-back" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                        </svg>
                        Voltar
                    </a>
                    <a href="{{ route('filament.admin.resources.events.edit', $event) }}" class="btn-edit" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        Editar
                    </a>
                </div>
            </div>

            <div class="event-meta">
                <div class="meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008z" />
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
                </div>

                <div class="meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span>{{ $event->location }}</span>
                </div>

                @if($event->is_paid)
                    <div class="meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                        <span>R$ {{ number_format($event->price, 2, ',', '.') }}</span>
                    </div>
                @endif

                <div class="meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.898 20.553L16.5 21.75l-.398-1.197a3.375 3.375 0 00-2.456-2.456L12.5 18l1.197-.398a3.375 3.375 0 002.456-2.456L16.5 14.25l.398 1.197a3.375 3.375 0 002.456 2.456L20.5 18l-1.197.398a3.375 3.375 0 00-2.456 2.456z" />
                    </svg>
                    <span>{{ ucfirst($event->getStatusLabel()) }}</span>
                </div>

                @if($event->capacity)
                    <div class="meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span>Capacidade: {{ $event->capacity }} pessoas</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card stat-success">
                <div class="stat-value">{{ $stats['total_participations'] }}</div>
                <div class="stat-label">Total de Inscrições</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 100%"></div>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-value">{{ $stats['paid_participations'] }}</div>
                <div class="stat-label">Inscrições Pagas</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: {{ $stats['occupancy_rate'] }}%"></div>
                </div>
                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                    {{ $stats['occupancy_rate'] }}% de ocupação
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-value">{{ $stats['pending_participations'] }}</div>
                <div class="stat-label">Pagamentos Pendentes</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: {{ $stats['total_participations'] > 0 ? ($stats['pending_participations'] / $stats['total_participations']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-value">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</div>
                <div class="stat-label">Receita Total</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 100%"></div>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-value">{{ $stats['used_tickets'] }} / {{ $stats['total_tickets'] }}</div>
                <div class="stat-label">Check-ins Realizados</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: {{ $stats['attendance_rate'] }}%"></div>
                </div>
                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
                    {{ $stats['attendance_rate'] }}% de comparecimento
                </div>
            </div>
        </div>

        <!-- Distribuição de Pagamentos -->
        @if($paymentDistribution->isNotEmpty())
            <div class="payment-distribution">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                    Distribuição de Pagamentos
                </h2>
                <div class="payment-methods">
                    @foreach($paymentDistribution as $payment)
                        <div class="payment-method">
                            <div class="payment-method-name">{{ $payment['method'] }}</div>
                            <div class="payment-method-count">{{ $payment['count'] }}</div>
                            <div class="payment-method-total">R$ {{ number_format($payment['total'], 2, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Lista de Participantes -->
        <div class="participants-section">
            <div class="participants-header">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Participantes Inscritos ({{ $stats['total_participations'] }})
                </h2>
            </div>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>