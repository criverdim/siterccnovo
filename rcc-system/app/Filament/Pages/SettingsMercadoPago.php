<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;

class SettingsMercadoPago extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $navigationLabel = 'Mercado Pago';
    protected static ?string $title = 'Mercado Pago';
    protected static ?string $slug = 'settings-mercadopago';

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->is_master_admin);
    }

    public function mount(): void
    {
        $row = Setting::firstOrCreate(['key' => 'mercadopago'], ['value' => []]);
        redirect()->route('filament.admin.resources.settings.edit', ['record' => $row->id])->send();
    }
}

