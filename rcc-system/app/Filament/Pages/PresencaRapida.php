<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PresencaRapida extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Gerenciamento';
    protected static ?string $title = 'Registro Rápido de Presença';

    protected static string $view = 'filament.pages.presenca-rapida';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage_pastoreio') ?? false;
    }
}

