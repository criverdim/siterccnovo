<?php

namespace App\Filament\Widgets;

use App\Models\GroupAttendance;
use Filament\Widgets\ChartWidget;

class TopRankingBarWidget extends ChartWidget
{
    protected static ?string $heading = 'Ranking de Presença';

    protected static ?string $description = 'Top 10 usuários mais presentes';

    protected static ?string $maxHeight = '260px';

    protected function getData(): array
    {
        try {
            $top = GroupAttendance::selectRaw('user_id, COUNT(*) as total')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->with('user')
                ->limit(10)
                ->get();
        } catch (\Throwable $e) {
            $top = collect([]);
        }

        return [
            'datasets' => [[
                'label' => 'Presenças',
                'data' => $top->pluck('total'),
                'backgroundColor' => '#c9a043',
                'borderColor' => '#a3802d',
                'borderWidth' => 1,
            ]],
            'labels' => $top->map(fn ($r) => $r->user?->name ?? 'Usuário '.$r->user_id),
        ];
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
