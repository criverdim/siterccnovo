<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsSocial extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-share';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $navigationLabel = 'Redes Sociais';
    protected static ?string $title = 'Redes Sociais';
    protected static ?string $slug = 'settings-social';

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->is_master_admin);
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'social'], ['value' => []]);
        redirect()->route('filament.admin.resources.settings.edit', ['record' => $row->id])->send();
    }
}

