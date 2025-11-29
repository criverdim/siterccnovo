<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static string $view = 'filament.pages.test-email';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return false;
    }

    public function send(): void
    {
        $cfg = Setting::where('key','email')->first();
        $to = $cfg?->value['test_recipient'] ?? null;
        if (!$cfg || !$to) {
            Notification::make()->title('Configure remetente e destinatário de teste').warning()->send();
            return;
        }
        try {
            Mail::raw('Teste de envio RCC', function($m) use ($to, $cfg){
                $fromEmail = $cfg->value['from_email'] ?? config('mail.from.address');
                $fromName = $cfg->value['from_name'] ?? config('mail.from.name');
                $m->from($fromEmail, $fromName)->to($to)->subject($cfg->value['subject'] ?? 'Teste');
            });
            Notification::make()->title('E-mail de teste enviado com sucesso').success()->send();
        } catch(\Throwable $e) {
            Notification::make()->title('Falha ao enviar')->body($e->getMessage())->danger()->send();
        }
    }
}
