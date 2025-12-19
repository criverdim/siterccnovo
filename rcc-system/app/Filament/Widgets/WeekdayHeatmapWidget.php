<?php

namespace App\Filament\Widgets;

use App\Models\GroupAttendance;
use Filament\Widgets\ChartWidget;

class WeekdayHeatmapWidget extends ChartWidget
{
    protected static ?string $heading = 'Presença por Dia da Semana';

    protected static ?string $description = 'Total de presenças por dia';

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $map = collect([
            'sunday' => 'Dom', 'monday' => 'Seg', 'tuesday' => 'Ter', 'wednesday' => 'Qua', 'thursday' => 'Qui', 'friday' => 'Sex', 'saturday' => 'Sáb',
        ]);

        $counts = $map->keys()->map(function ($day) {
            try {
                return GroupAttendance::whereRaw('DAYOFWEEK(date) = ?', [$this->weekdayToNumber($day)])->count();
            } catch (\Throwable $e) {
                return 0;
            }
        });

        return [
            'datasets' => [[
                'label' => 'Presenças',
                'data' => $counts,
                'backgroundColor' => $counts->map(fn ($c) => $c > 0 ? '#059669' : '#9CA3AF'),
                'borderRadius' => 6,
            ]],
            'labels' => $map->values(),
        ];
    }

    protected function weekdayToNumber(string $day): string
    {
        return match ($day) {
            'sunday' => '1',
            'monday' => '2',
            'tuesday' => '3',
            'wednesday' => '4',
            'thursday' => '5',
            'friday' => '6',
            'saturday' => '7',
        };
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
                'title' => ['display' => false],
            ],
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
        ];
    }
}
