<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsTemplates extends Page
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Configurações';

    protected static ?string $navigationLabel = 'Templates de Automação';

    protected static ?string $title = 'Templates de Automação';

    protected static ?string $slug = 'settings-templates';

    public static function canAccess(): bool
    {
        $u = auth()->user();

        return (bool) ($u?->can_access_admin || $u?->is_master_admin || ($u?->role === 'admin'));
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'templates'], ['value' => []]);
        $url = \App\Filament\Resources\SettingResource::getUrl('edit', ['record' => $row->id]);
        $this->redirect($url);
    }
}
