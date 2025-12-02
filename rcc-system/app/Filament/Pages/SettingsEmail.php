<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsEmail extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $navigationLabel = 'Servidor de Email';
    protected static ?string $title = 'Servidor de Email';
    protected static ?string $slug = 'settings-email';

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->is_master_admin);
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'email'], ['value' => []]);
        redirect()->route('filament.admin.resources.settings.edit', ['record' => $row->id])->send();
    }
}

