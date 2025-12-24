<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardAlerts extends BaseWidget
{
    protected static ?int $sort = -1; // Topo

    protected function getStats(): array
    {
        $alerts = [];

        // 1. Pagamentos pendentes
        $pendingPayments = Payment::where('status', 'pending')->count();
        if ($pendingPayments > 0) {
            $alerts[] = Stat::make('Pagamentos Pendentes', (string) $pendingPayments)
                ->description('Requer atenção')
                ->color('warning')
                ->descriptionIcon('heroicon-m-exclamation-triangle');
        }

        // 2. Eventos iniciando em breve (próximos 3 dias)
        $eventsStarting = Event::where('start_date', '>=', now())
            ->where('start_date', '<=', now()->addDays(3))
            ->where('is_active', true)
            ->count();
        
        if ($eventsStarting > 0) {
            $alerts[] = Stat::make('Eventos Próximos', (string) $eventsStarting)
                ->description('Iniciam em até 3 dias')
                ->color('info')
                ->descriptionIcon('heroicon-m-clock');
        }

        // 3. Poucas vagas restantes (< 10% de capacidade)
        $eventsLowCapacity = Event::where('is_active', true)
            ->where('capacity', '>', 0)
            ->get()
            ->filter(function ($event) {
                $sold = $event->participations()->where('payment_status', 'approved')->count();
                return ($event->capacity - $sold) < ($event->capacity * 0.1);
            })
            ->count();

        if ($eventsLowCapacity > 0) {
            $alerts[] = Stat::make('Eventos Lotando', (string) $eventsLowCapacity)
                ->description('Menos de 10% de vagas')
                ->color('danger')
                ->descriptionIcon('heroicon-m-fire');
        }

        return $alerts;
    }
}
