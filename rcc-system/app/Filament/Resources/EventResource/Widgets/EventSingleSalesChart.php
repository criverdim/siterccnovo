<?php

namespace App\Filament\Resources\EventResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\Payment;
use Carbon\CarbonPeriod;

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
                ->whereDate('created_at', $date)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Vendas',
                    'data' => $values,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
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
