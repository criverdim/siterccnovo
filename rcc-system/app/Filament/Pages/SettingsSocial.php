<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsSocial extends Page
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationGroup = 'Configurações';

    protected static ?string $navigationLabel = 'Redes Sociais';

    protected static ?string $title = 'Redes Sociais';

    protected static ?string $slug = 'settings-social';

    public static function canAccess(): bool
    {
        $u = auth()->user();

        return (bool) ($u?->can_access_admin || $u?->is_master_admin || ($u?->role === 'admin'));
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'social'], ['value' => []]);
        $url = \App\Filament\Resources\SettingResource::getUrl('edit', ['record' => $row->id]);
        $this->redirect($url);
    }
}
