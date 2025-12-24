<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class EventCheckinsBarChart extends ChartWidget
{
    protected static ?string $heading = 'Check-ins por dia (últimos 14 dias)';
    protected static ?string $pollingInterval = '20s';

    protected function getData(): array
    {
        $period = CarbonPeriod::create(now()->subDays(13)->startOfDay(), now()->startOfDay());
        $labels = [];
        $values = [];
        foreach ($period as $date) {
            $labels[] = $date->format('d/m');
            $values[] = (int) Ticket::whereDate('used_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Check-ins',
                    'data' => $values,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

