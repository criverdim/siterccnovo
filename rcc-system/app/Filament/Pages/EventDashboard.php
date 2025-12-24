<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class EventDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Eventos';
    protected static ?string $title = 'Dashboard de Eventos';

    protected static string $view = 'filament.event-dashboard';
}
