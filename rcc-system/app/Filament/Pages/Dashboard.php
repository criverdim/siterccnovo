<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MonthlyLineWidget;
use App\Filament\Widgets\PresencePieWidget;
use App\Filament\Widgets\TopRankingBarWidget;
use App\Filament\Widgets\WeekdayHeatmapWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AdminStatsOverview::class,
            PresencePieWidget::class,
            MonthlyLineWidget::class,
            TopRankingBarWidget::class,
            WeekdayHeatmapWidget::class,
        ];
    }
}
