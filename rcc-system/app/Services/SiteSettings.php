<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SiteSettings
{
    protected int $ttl = 900;

    protected function remember(string $key, \Closure $resolver)
    {
        return Cache::remember('site_settings:'.$key, $this->ttl, $resolver);
    }

    public function site(): array
    {
        $defaults = [
            'address' => env('SITE_ADDRESS', 'Rua Exemplo, 123 - Cidade/UF'),
            'phone' => env('SITE_PHONE', '(00) 0000-0000'),
            'whatsapp' => env('SITE_WHATSAPP', '(00) 90000-0000'),
            'email' => env('SITE_EMAIL', 'contato@rcc.local'),
        ];

        return $this->remember('site', function () use ($defaults) {
            try {
                if (! Schema::hasTable('settings')) {

                    return $defaults;
                }
                $s = Setting::where('key', 'site')->first();
                $vals = $s?->value ?? [];

                return array_merge($defaults, array_filter($vals, fn ($v) => $v !== null));
            } catch (\Throwable $e) {
                return $defaults;
            }
        });
    }

    public function social(): array
    {
        $defaults = [
            'instagram' => env('SOCIAL_INSTAGRAM', '#'),
            'facebook' => env('SOCIAL_FACEBOOK', '#'),
            'youtube' => env('SOCIAL_YOUTUBE', '#'),
            'whatsapp' => env('SOCIAL_WHATSAPP', '#'),
            'tiktok' => env('SOCIAL_TIKTOK', '#'),
        ];

        return $this->remember('social', function () use ($defaults) {
            try {
                if (! Schema::hasTable('settings')) {

                    return $defaults;
                }
                $s = Setting::where('key', 'social')->first();
                $vals = $s?->value ?? [];

                return array_merge($defaults, array_filter($vals, fn ($v) => $v !== null));
            } catch (\Throwable $e) {
                return $defaults;
            }
        });
    }

    public function brandLogoUrl(): ?string
    {
        return $this->remember('brand_logo', function () {
            try {
                if (! Schema::hasTable('settings')) {

                    return null;
                }
                $b = Setting::where('key', 'brand')->first();
                $path = $b?->value['logo'] ?? null;
                if ($path) {
                    $base = (string) config('filesystems.disks.public.url');
                    $cleanPath = ltrim($path, '/');
                    $reqHost = null;
                    try {
                        $reqHost = request()->getHost();
                    } catch (\Throwable $e2) {
                    }
                    $isLocal = in_array($reqHost, ['127.0.0.1', 'localhost'], true);
                    if ($base) {
                        if ($isLocal) {

                            return '/storage/'.$cleanPath;
                        }

                        return rtrim($base, '/').'/'.$cleanPath;
                    }
                    $url = Storage::disk('public')->url($path);
                    $app = rtrim((string) config('app.url'), '/');
                    $startsLocal = function (string $u): bool {
                        return str_starts_with($u, 'http://127.0.0.1')
                            || str_starts_with($u, 'https://127.0.0.1')
                            || str_starts_with($u, 'http://localhost')
                            || str_starts_with($u, 'https://localhost');
                    };
                    if ($app && $startsLocal((string) $url)) {

                        return $app.'/storage/'.$cleanPath;
                    }

                    return $url;
                }

                return null;
            } catch (\Throwable $e) {
                return null;
            }
        });
    }

    public function all(): array
    {
        try {
            return [
                'site' => $this->site(),
                'social' => $this->social(),
                'brand_logo' => $this->brandLogoUrl(),
            ];
        } catch (\Throwable $e) {
            return [
                'site' => [
                    'address' => env('SITE_ADDRESS', 'Rua Exemplo, 123 - Cidade/UF'),
                    'phone' => env('SITE_PHONE', '(00) 0000-0000'),
                    'whatsapp' => env('SITE_WHATSAPP', '(00) 90000-0000'),
                    'email' => env('SITE_EMAIL', 'contato@rcc.local'),
                ],
                'social' => [
                    'instagram' => env('SOCIAL_INSTAGRAM', '#'),
                    'facebook' => env('SOCIAL_FACEBOOK', '#'),
                    'youtube' => env('SOCIAL_YOUTUBE', '#'),
                    'whatsapp' => env('SOCIAL_WHATSAPP', '#'),
                    'tiktok' => env('SOCIAL_TIKTOK', '#'),
                ],
                'brand_logo' => null,
            ];
        }
    }

    public function invalidate(?string $key = null): void
    {
        if ($key) {
            Cache::forget('site_settings:'.$key);
            if ($key === 'brand') {
                Cache::forget('site_settings:brand_logo');
            }

            return;
        }
        Cache::forget('site_settings:site');
        Cache::forget('site_settings:social');
        Cache::forget('site_settings:brand_logo');
    }
}
