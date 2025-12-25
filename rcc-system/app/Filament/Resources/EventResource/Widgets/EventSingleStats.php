<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class EventSingleStats extends BaseWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        if (! $this->record) {
            return [];
        }

        /** @var Event $event */
        $event = $this->record;

        $sold = $event->participations()->where('payment_status', 'approved')->count();
        $pending = $event->participations()->where('payment_status', 'pending')->count();
        $revenue = $event->payments()->where('status', 'approved')->sum('amount');

        $totalTickets = $event->tickets()->count();
        $usedTickets = $event->tickets()->where('status', 'used')->count();

        $attendanceRate = $totalTickets > 0 ? round(($usedTickets / $totalTickets) * 100, 1) : 0;
        $occupancyRate = $event->capacity > 0 ? round(($sold / $event->capacity) * 100, 1) : 0;

        return [
            Stat::make('Ingressos Vendidos', (string) $sold)
                ->description("Ocupação: {$occupancyRate}%")
                ->descriptionIcon('heroicon-o-chart-bar')
                ->icon('heroicon-o-ticket')
                ->extraAttributes(['class' => 'event-stat brand-stat-green'])
                ->color('success'),

            Stat::make('Receita Total', 'R$ '.number_format($revenue, 2, ',', '.'))
                ->description('Ticket médio: R$ '.($sold > 0 ? number_format($revenue / $sold, 2, ',', '.') : '0,00'))
                ->descriptionIcon('heroicon-o-calculator')
                ->icon('heroicon-o-banknotes')
                ->extraAttributes(['class' => 'event-stat brand-stat-gold'])
                ->color('warning'),

            Stat::make('Check-ins Realizados', "{$usedTickets} / {$totalTickets}")
                ->description("Comparecimento: {$attendanceRate}%")
                ->descriptionIcon('heroicon-o-check-circle')
                ->icon('heroicon-o-check-badge')
                ->extraAttributes(['class' => 'event-stat brand-stat-blue'])
                ->color('primary'),

            Stat::make('Pagamentos Pendentes', (string) $pending)
                ->description('Aguardando aprovação')
                ->descriptionIcon('heroicon-o-clock')
                ->icon('heroicon-o-exclamation-triangle')
                ->extraAttributes(['class' => 'event-stat brand-stat-yellow'])
                ->color('danger'),
        ];
    }
}
