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
            return new \App\Services\SiteSettings;
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
        try {
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
        } catch (\Throwable $e) {
        }

        try {
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
        } catch (\Throwable $e) {
        }

        try {
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
        } catch (\Throwable $e) {
        }

        try {
            if (! Schema::hasTable('sessions')) {
                config(['session.driver' => 'file']);
            }
        } catch (\Throwable $e) {
            config(['session.driver' => 'file']);
        }

        try {
            $cacheTable = env('DB_CACHE_TABLE', 'cache');
            if (! Schema::hasTable($cacheTable)) {
                config(['cache.default' => 'array']);
            }
        } catch (\Throwable $e) {
            config(['cache.default' => 'array']);
        }

        try {
            \Illuminate\Support\Facades\View::composer('*', function ($view) {
                try {
                    $svc = app(\App\Services\SiteSettings::class);
                    $view->with('siteSettings', $svc->all());
                } catch (\Throwable $e) {
                    $view->with('siteSettings', [
                        'site' => [],
                        'social' => [],
                        'brand_logo' => null,
                    ]);
                }
            });
        } catch (\Throwable $e) {
        }

        try {
            $base = rtrim((string) config('app.url'), '/');
            if ($base) {
                config(['filesystems.disks.public.url' => $base.'/storage']);
            }
        } catch (\Throwable $e) {
        }

        try {
            if (app()->environment('local') || app()->environment('development')) {
                $localUrl = env('APP_URL_LOCAL');
                if ($localUrl) {
                    config(['app.url' => $localUrl]);
                    config(['filesystems.disks.public.url' => rtrim($localUrl, '/').'/storage']);
                }
            }
        } catch (\Throwable $e) {
        }

        try {
            $appUrl = (string) config('app.url');
            $reqHost = null;
            try {
                $reqHost = request()->getHost();
            } catch (\Throwable $e2) {
            }
            $isLocalHost = in_array($reqHost, ['127.0.0.1', 'localhost'], true);
            if (! $isLocalHost && str_starts_with($appUrl, 'https://')) {
                \Illuminate\Support\Facades\URL::forceScheme('https');
            }
        } catch (\Throwable $e) {
        }

        try {
            $frameworkPath = storage_path('framework');
            $free = @disk_free_space($frameworkPath) ?: 0;
            $threshold = 50 * 1024 * 1024;
            if ($free <= 0 || $free < $threshold) {
                $tmpBase = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
                $viewsPath = $tmpBase.DIRECTORY_SEPARATOR.'laravel-views';
                $sessionsPath = $tmpBase.DIRECTORY_SEPARATOR.'laravel-sessions';
                @mkdir($viewsPath, 0777, true);
                @mkdir($sessionsPath, 0777, true);
                config(['view.compiled' => $viewsPath]);
                config(['session.files' => $sessionsPath]);
                try {
                    config(['logging.default' => 'errorlog']);
                } catch (\Throwable $e2) {
                }
            }
        } catch (\Throwable $e) {
        }

        try {
            $overrideBase = env('RUNTIME_BASE');
            if ($overrideBase) {
                $base = rtrim($overrideBase, DIRECTORY_SEPARATOR);
                $viewsPath = $base.DIRECTORY_SEPARATOR.'views';
                $sessionsPath = $base.DIRECTORY_SEPARATOR.'sessions';
                $cachePath = $base.DIRECTORY_SEPARATOR.'cache';
                $logsPath = $base.DIRECTORY_SEPARATOR.'logs';
                @mkdir($viewsPath, 0777, true);
                @mkdir($sessionsPath, 0777, true);
                @mkdir($cachePath, 0777, true);
                @mkdir($logsPath, 0777, true);
                config(['view.compiled' => $viewsPath]);
                if ((string) config('session.driver') === 'file') {
                    config(['session.files' => $sessionsPath]);
                }
                config(['cache.stores.file.path' => $cachePath]);
                config(['cache.stores.file.lock_path' => $cachePath]);
                try {
                    $t = rtrim($cachePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'.__cache_test';
                    $ok = @file_put_contents($t, 'ok') !== false;
                    @unlink($t);
                    if (! $ok) {
                        config(['cache.default' => 'array']);
                    }
                } catch (\Throwable $e5) {
                    try {
                        config(['cache.default' => 'array']);
                    } catch (\Throwable $e6) {
                    }
                }
                try {
                    config(['logging.channels.daily.path' => $logsPath.DIRECTORY_SEPARATOR.'laravel.log']);
                } catch (\Throwable $e4) {
                }
            }
        } catch (\Throwable $e) {
        }

        try {
            $defaultViewsPath = storage_path('framework/views');
            $defaultSessionsPath = storage_path('framework/sessions');
            $needsViewsFallback = ! @is_dir($defaultViewsPath) || ! @is_writable($defaultViewsPath);
            $needsSessionsFallback = ! @is_dir($defaultSessionsPath) || ! @is_writable($defaultSessionsPath);
            // se os diretórios pertencem a root, força fallback
            $vStat = @stat($defaultViewsPath);
            $sStat = @stat($defaultSessionsPath);
            if (($vStat && ($vStat['uid'] ?? null) === 0) || ($sStat && ($sStat['uid'] ?? null) === 0)) {
                $needsViewsFallback = true;
                $needsSessionsFallback = true;
            }
            if ($needsViewsFallback || $needsSessionsFallback) {
                $tmpBase = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
                $viewsPath = $tmpBase.DIRECTORY_SEPARATOR.'laravel-views';
                $sessionsPath = $tmpBase.DIRECTORY_SEPARATOR.'laravel-sessions';
                @mkdir($viewsPath, 0777, true);
                @mkdir($sessionsPath, 0777, true);
                if ($needsViewsFallback) {
                    config(['view.compiled' => $viewsPath]);
                }
                if ($needsSessionsFallback) {
                    config(['session.files' => $sessionsPath]);
                }
                try {
                    config(['logging.default' => 'errorlog']);
                } catch (\Throwable $e3) {
                }
            }
        } catch (\Throwable $e) {
        }

        try {
            $compiled = (string) config('view.compiled');
            if ($compiled) {
                $compiledDir = is_dir($compiled) ? $compiled : dirname($compiled);
                $needsFallback = ! @is_dir($compiledDir) || ! @is_writable($compiledDir);
                if (! $needsFallback) {
                    $t = rtrim($compiledDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'.__vc_test';
                    $ok = @file_put_contents($t, 'ok') !== false;
                    @unlink($t);
                    if (! $ok) {
                        $needsFallback = true;
                    }
                }
                if ($needsFallback) {
                    $tmpBase = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
                    $viewsPath = $tmpBase.DIRECTORY_SEPARATOR.'laravel-views';
                    @mkdir($viewsPath, 0777, true);
                    config(['view.compiled' => $viewsPath]);
                }
            }
        } catch (\Throwable $e) {
        }

        try {
            $driver = (string) config('session.driver');
            if ($driver === 'file') {
                $sessPath = (string) config('session.files');
                if ($sessPath) {
                    $sessDir = is_dir($sessPath) ? $sessPath : dirname($sessPath);
                    $needsFallback = ! @is_dir($sessDir) || ! @is_writable($sessDir);
                    if (! $needsFallback) {
                        $t2 = rtrim($sessDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'.__sf_test';
                        $ok2 = @file_put_contents($t2, 'ok') !== false;
                        @unlink($t2);
                        if (! $ok2) {
                            $needsFallback = true;
                        }
                    }
                    if ($needsFallback) {
                        $tmpBase = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
                        $sessionsPath = $tmpBase.DIRECTORY_SEPARATOR.'laravel-sessions';
                        @mkdir($sessionsPath, 0777, true);
                        config(['session.files' => $sessionsPath]);
                        try {
                            config(['logging.default' => 'errorlog']);
                        } catch (\Throwable $e3) {
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
        }
    }
}
