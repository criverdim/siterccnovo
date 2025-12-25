<x-filament-panels::page>
    @php
        $events = $this->getEvents();
        $colors = $this->getEventColors();
    @endphp

    <style>
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            padding: 1rem 0;
        }

        .event-card {
            background: linear-gradient(135deg, var(--card-bg-start), var(--card-bg-end));
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
            min-height: 280px;
        }

        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .event-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--accent-color-light));
        }

        .event-card-content {
            padding: 1.5rem;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 240px;
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .event-title {
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1.3;
            margin: 0;
            flex: 1;
            margin-right: 1rem;
        }

        .participants-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .participants-badge svg {
            width: 16px;
            height: 16px;
        }

        .event-info {
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .info-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .info-item span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-actions {
            display: flex;
            gap: 0.75rem;
            position: absolute;
            left: 1.5rem;
            right: 1.5rem;
            bottom: 1.25rem;
        }

        .btn-details {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            flex: 1;
            justify-content: center;
        }

        .btn-details:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #ef4444;
            border: 1px solid #b91c1c;
            color: #ffffff;
            padding: 0.75rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: #ffffff;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            opacity: 0.5;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        @media (max-width: 768px) {
            .events-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .event-card-content {
                padding: 1.25rem;
            }
            
            .event-title {
                font-size: 1.125rem;
            }
            
            .event-actions {
                flex-direction: column;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }

        /* Cores do sistema baseadas no documento */
        .event-card[data-color="#10B981"] {
            --card-bg-start: #10B981;
            --card-bg-end: #059669;
            --accent-color: #10B981;
            --accent-color-light: #34d399;
        }

        .event-card[data-color="#3B82F6"] {
            --card-bg-start: #3B82F6;
            --card-bg-end: #2563EB;
            --accent-color: #3B82F6;
            --accent-color-light: #60a5fa;
        }

        .event-card[data-color="#F59E0B"] {
            --card-bg-start: #F59E0B;
            --card-bg-end: #D97706;
            --accent-color: #F59E0B;
            --accent-color-light: #fbbf24;
        }

        .event-card[data-color="#EF4444"] {
            --card-bg-start: #EF4444;
            --card-bg-end: #DC2626;
            --accent-color: #EF4444;
            --accent-color-light: #f87171;
        }

        .event-card[data-color="#8B5CF6"] {
            --card-bg-start: #8B5CF6;
            --card-bg-end: #7C3AED;
            --accent-color: #8B5CF6;
            --accent-color-light: #a78bfa;
        }

        .event-card[data-color="#06B6D4"] {
            --card-bg-start: #06B6D4;
            --card-bg-end: #0891B2;
            --accent-color: #06B6D4;
            --accent-color-light: #22d3ee;
        }

        .event-card[data-color="#84CC16"] {
            --card-bg-start: #84CC16;
            --card-bg-end: #65A30D;
            --accent-color: #84CC16;
            --accent-color-light: #a3e635;
        }

        .event-card[data-color="#F97316"] {
            --card-bg-start: #F97316;
            --card-bg-end: #EA580C;
            --accent-color: #F97316;
            --accent-color-light: #fb923c;
        }
    </style>

    

    @if($events->isEmpty())
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008zM12 15h.008v.008H12v-.008zM12 17.25h.008v.008H12v-.008zM9 15h.008v.008H9v-.008zM9 17.25h.008v.008H9v-.008zM15 15h.008v.008H15v-.008zM15 17.25h.008v.008H15v-.008z" />
            </svg>
            <h3>Nenhum evento encontrado</h3>
            <p>Comece criando seu primeiro evento</p>
        </div>
    @else
        <div class="events-grid">
            @foreach($events as $index => $event)
                @php
                    $color = $this->getEventColor($index);
                    $participantsCount = $event->participations_count ?? 0;
                    $isActive = $event->isActive();
                    $statusColor = $isActive ? 'success' : 'gray';
                    $statusText = $isActive ? 'Ativo' : 'Inativo';
                @endphp
                
                <div class="event-card" data-color="{{ $color }}" onclick="window.location.href='{{ \App\Filament\Resources\EventResource\Pages\EventSingleDashboard::getUrl(['record' => $event]) }}'">
                    <div class="event-card-content">
                        <div class="event-header">
                            <h3 class="event-title">{{ $event->name }}</h3>
                            <div class="participants-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                {{ $participantsCount }} inscritos
                            </div>
                        </div>

                        <div class="event-info">
                            <div class="info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 12.75h.008v.008H12v-.008z" />
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span>{{ $event->location }}</span>
                            </div>

                            <div class="info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.898 20.553L16.5 21.75l-.398-1.197a3.375 3.375 0 00-2.456-2.456L12.5 18l1.197-.398a3.375 3.375 0 002.456-2.456L16.5 14.25l.398 1.197a3.375 3.375 0 002.456 2.456L20.5 18l-1.197.398a3.375 3.375 0 00-2.456 2.456z" />
                                </svg>
                                <span>{{ ucfirst($event->getStatusLabel()) }}</span>
                            </div>
                        </div>

                        <div class="event-actions">
                            <a href="{{ \App\Filament\Resources\EventResource\Pages\EventSingleDashboard::getUrl(['record' => $event]) }}" 
                               class="btn-details"
                               wire:navigate
                               onclick="event.stopPropagation()"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 16px; height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Ver detalhes
                            </a>
                            
                            <button wire:click="deleteEvent({{ $event->id }})" 
                                    class="btn-delete"
                                    onclick="event.stopPropagation(); if(!confirm('Tem certeza que deseja excluir este evento?')) { return false; }"
                                    title="Excluir evento">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
