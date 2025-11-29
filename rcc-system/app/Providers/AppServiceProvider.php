<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('testing')) {
            return;
        }
        if (Schema::hasTable('settings')) {
            $cfg = Setting::where('key', 'email')->first();
            if ($cfg && is_array($cfg->value)) {
                $host = $cfg->value['host'] ?? null;
                $port = (int) ($cfg->value['port'] ?? 0);
                $username = $cfg->value['username'] ?? null;
                $password = $cfg->value['password'] ?? null;
                $enc = $cfg->value['encryption'] ?? null;
                $fromEmail = $cfg->value['from_email'] ?? null;
                $fromName = $cfg->value['from_name'] ?? null;
                $scheme = ($enc === 'ssl' || $port === 465) ? 'smtps' : 'smtp';
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.scheme' => $scheme,
                    'mail.mailers.smtp.host' => $host,
                    'mail.mailers.smtp.port' => $port,
                    'mail.mailers.smtp.username' => $username,
                    'mail.mailers.smtp.password' => $password,
                    'mail.mailers.smtp.timeout' => 10,
                    'mail.from.address' => $fromEmail ?: config('mail.from.address'),
                    'mail.from.name' => $fromName ?: config('mail.from.name'),
                ]);
            }
        }
    }
}
