<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class EventSalesLineChart extends ChartWidget
{
    protected static ?string $heading = 'Vendas por dia (últimos 14 dias)';
    protected static ?string $pollingInterval = '20s';

    protected function getData(): array
    {
        $period = CarbonPeriod::create(now()->subDays(13)->startOfDay(), now()->startOfDay());
        $labels = [];
        $values = [];
        foreach ($period as $date) {
            $labels[] = $date->format('d/m');
            $values[] = (int) Payment::whereDate('created_at', $date)->where('status', 'approved')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pagamentos aprovados',
                    'data' => $values,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

