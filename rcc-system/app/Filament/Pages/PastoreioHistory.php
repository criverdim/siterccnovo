<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PastoreioHistory extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Gerenciamento';

    protected static ?string $title = 'Histórico Pastoral';

    protected static string $view = 'filament.pages.pastoreio-history';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage_pastoreio') ?? false;
    }
}
