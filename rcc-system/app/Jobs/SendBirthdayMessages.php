<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBirthdayMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = now()->format('m-d');
        $template = optional(Setting::where('key','templates')->first())->value['template_aniversario'] ?? 'Feliz aniversário, {{name}}!';
        $users = User::whereNotNull('birth_date')->get();
        foreach ($users as $u) {
            if ($u->birth_date && $u->birth_date->format('m-d') === $today) {
                $msg = str_replace('{{name}}', $u->name, $template);
                \Log::info('[Birthday] '.$u->id.' '.$msg);
                // Integrar com WhatsApp/E-mail conforme configuração (omisso por segurança)
            }
        }
    }
}

