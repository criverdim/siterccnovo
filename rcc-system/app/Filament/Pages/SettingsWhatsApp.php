<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsWhatsApp extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $navigationLabel = 'WhatsApp';
    protected static ?string $title = 'WhatsApp';
    protected static ?string $slug = 'settings-whatsapp';

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->is_master_admin);
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'whatsapp'], ['value' => []]);
        redirect()->route('filament.admin.resources.settings.edit', ['record' => $row->id])->send();
    }
}

