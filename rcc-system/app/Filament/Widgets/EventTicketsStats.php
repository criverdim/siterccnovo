<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class EventTicketsStats extends BaseWidget
{
    protected function getStats(): array
    {
        $generated = (int) Ticket::count();
        $used = (int) Ticket::where('status', 'used')->count();
        $active = (int) Ticket::where('status', 'active')->count();
        $cancelled = (int) Ticket::where('status', 'cancelled')->count();

        $eventsCount = (int) Event::count();

        return [
            Stat::make('Gerados', (string) $generated)->color('info'),
            Stat::make('Utilizados', (string) $used)->color('gray'),
            Stat::make('Ativos', (string) $active)->color('success'),
            Stat::make('Cancelados', (string) $cancelled)->color('danger'),
            Stat::make('Eventos', (string) $eventsCount)->color('primary'),
        ];
    }
}
