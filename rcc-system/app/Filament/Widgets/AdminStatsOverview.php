<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $members = User::count();
        $groups = Group::count();
        $events = Event::where('is_active', true)->count();
        $registrationsToday = User::whereDate('created_at', today())->count();

        return [
            Stat::make('Membros', (string) $members)
                ->description('Total cadastrado')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Grupos', (string) $groups)
                ->description('Grupos de oração ativos')
                ->descriptionIcon('heroicon-m-queue-list')
                ->color('primary'),
            Stat::make('Eventos ativos', (string) $events)
                ->description('Próximos e em andamento')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
            Stat::make('Cadastros hoje', (string) $registrationsToday)
                ->description('Últimas 24h')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
        ];
    }
}
