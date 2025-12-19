<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsEmail extends Page
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Configurações';

    protected static ?string $navigationLabel = 'Servidor de Email';

    protected static ?string $title = 'Servidor de Email';

    protected static ?string $slug = 'settings-email';

    public static function canAccess(): bool
    {
        $u = auth()->user();

        return (bool) ($u?->can_access_admin || $u?->is_master_admin || ($u?->role === 'admin'));
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'email'], ['value' => []]);
        $url = \App\Filament\Resources\SettingResource::getUrl('edit', ['record' => $row->id]);
        $this->redirect($url);
    }
}
