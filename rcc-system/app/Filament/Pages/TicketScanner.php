<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TicketScanner extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Scanner de Ingressos';
    protected static ?string $navigationGroup = 'Eventos';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.ticket-scanner';
}
