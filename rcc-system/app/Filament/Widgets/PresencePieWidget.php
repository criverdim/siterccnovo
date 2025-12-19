<?php

namespace App\Filament\Widgets;

use App\Models\GroupAttendance;
use Filament\Widgets\ChartWidget;

class PresencePieWidget extends ChartWidget
{
    protected static ?string $heading = 'Presença Geral';

    protected static ?string $description = 'Distribuição de presenças recentes vs anteriores';

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        try {
            $total = GroupAttendance::count();
        } catch (\Throwable $e) {
            $total = 0;
        }
        try {
            $last30 = GroupAttendance::whereDate('date', '>=', now()->subDays(30))->count();
        } catch (\Throwable $e) {
            $last30 = 0;
        }
        $older = max($total - $last30, 0);

        return [
            'datasets' => [[
                'label' => 'Presenças',
                'data' => [$last30, $older],
                'backgroundColor' => ['#059669', '#c9a043'],
                'borderColor' => ['#047857', '#b3862f'],
                'borderWidth' => 1,
            ]],
            'labels' => ['Últimos 30 dias', 'Anterior'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'bottom'],
                'title' => ['display' => false],
            ],
        ];
    }
}
