<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class EventSingleSalesChart extends ChartWidget
{
    public ?Model $record = null;

    protected static ?string $heading = 'Vendas por Dia (Aprovadas)';

    protected function getData(): array
    {
        if (! $this->record) {
            return [];
        }

        /** @var Event $event */
        $event = $this->record;

        // Últimos 14 dias ou desde a criação do evento se for recente
        $start = now()->subDays(14)->startOfDay();
        if ($event->created_at > $start) {
            $start = $event->created_at->startOfDay();
        }
        $end = now()->endOfDay();

        $period = CarbonPeriod::create($start, $end);
        $labels = [];
        $values = [];

        foreach ($period as $date) {
            $labels[] = $date->format('d/m');
            $values[] = (int) $event->payments()
                ->where('status', 'approved')
                ->whereDate('paid_at', $date)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Vendas',
                    'data' => $values,
                    'borderColor' => '#006036',
                    'backgroundColor' => 'rgba(0, 96, 54, 0.1)',
                    'fill' => true,
                    'tension' => 0.35,
                    'borderWidth' => 3,
                    'pointRadius' => 3,
                    'pointHoverRadius' => 5,
                    'pointBackgroundColor' => '#006036',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'grid' => [
                        'color' => 'rgba(148, 163, 184, 0.25)',
                    ],
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
