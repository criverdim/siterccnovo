<?php

namespace App\Filament\Widgets;

use App\Models\GroupAttendance;
use Filament\Widgets\ChartWidget;

class MonthlyLineWidget extends ChartWidget
{
    protected static ?string $heading = 'Evolução Mensal';

    protected static ?string $description = 'Total de presenças por mês';

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $months = collect(range(0, 11))->reverse()->map(function ($i) {
            $m = now()->copy()->subMonths($i);
            try {
                $count = GroupAttendance::whereMonth('date', $m->month)->whereYear('date', $m->year)->count();
            } catch (\Throwable $e) {
                $count = 0;
            }

            return ['label' => $m->format('M/Y'), 'count' => $count];
        });

        return [
            'datasets' => [[
                'label' => 'Presenças',
                'data' => $months->pluck('count'),
                'borderColor' => '#059669',
                'backgroundColor' => 'rgba(5,150,105,0.2)',
                'tension' => 0.3,
                'fill' => true,
                'pointBackgroundColor' => '#059669',
                'pointRadius' => 3,
            ]],
            'labels' => $months->pluck('label'),
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
                'legend' => ['display' => false],
                'title' => ['display' => false],
                'tooltip' => ['enabled' => true],
            ],
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
        ];
    }
}
