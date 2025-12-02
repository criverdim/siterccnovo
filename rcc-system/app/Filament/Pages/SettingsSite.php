<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsSite extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $navigationLabel = 'Site (Endereço & Contato)';
    protected static ?string $title = 'Site (Endereço & Contato)';
    protected static ?string $slug = 'settings-site';

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->is_master_admin);
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'site'], ['value' => []]);
        redirect()->route('filament.admin.resources.settings.edit', ['record' => $row->id])->send();
    }
}

