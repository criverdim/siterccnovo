<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SiteSettings::class, function () {
            return new \App\Services\SiteSettings();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage_pastoreio', function (?User $user) {
            if (! $user) {
                return false;
            }

            return ($user->status === 'active') && in_array($user->role, ['servo', 'admin'], true);
        });
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

        if (Schema::hasTable('settings')) {
            $mp = Setting::where('key', 'mercadopago')->first();
            if ($mp && is_array($mp->value)) {
                config([
                    'services.mercadopago.access_token' => $mp->value['access_token'] ?? config('services.mercadopago.access_token'),
                    'services.mercadopago.public_key' => $mp->value['public_key'] ?? config('services.mercadopago.public_key'),
                    'services.mercadopago.mode' => $mp->value['mode'] ?? config('services.mercadopago.mode'),
                    'services.mercadopago.webhook_url' => $mp->value['webhook_url'] ?? config('services.mercadopago.webhook_url'),
                ]);
            }
        }

        if (Schema::hasTable('settings')) {
            $wa = Setting::where('key', 'whatsapp')->first();
            if ($wa && is_array($wa->value)) {
                config([
                    'services.whatsapp.url' => $wa->value['url'] ?? config('services.whatsapp.url'),
                    'services.whatsapp.token' => $wa->value['token'] ?? config('services.whatsapp.token'),
                    'services.whatsapp.phone_id' => $wa->value['phone_id'] ?? config('services.whatsapp.phone_id'),
                    'services.whatsapp.enabled' => $wa->value['enabled'] ?? config('services.whatsapp.enabled'),
                ]);
            }
        }

        try {
            \Illuminate\Support\Facades\View::composer('*', function ($view) {
                $svc = app(\App\Services\SiteSettings::class);
                $view->with('siteSettings', $svc->all());
            });
        } catch (\Throwable $e) {
        }
    }
}
