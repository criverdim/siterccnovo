<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsSms extends Page
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Configurações';

    protected static ?string $navigationLabel = 'Servidor de SMS';

    protected static ?string $title = 'Servidor de SMS';

    protected static ?string $slug = 'settings-sms';

    public static function canAccess(): bool
    {
        $u = auth()->user();

        return (bool) ($u?->can_access_admin || $u?->is_master_admin || ($u?->role === 'admin'));
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'sms'], ['value' => []]);
        $url = \App\Filament\Resources\SettingResource::getUrl('edit', ['record' => $row->id]);
        $this->redirect($url);
    }
}
