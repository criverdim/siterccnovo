<?php

namespace App\Providers\Filament;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Setting;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $brandSetting = null;
        try {
            if (Schema::hasTable('settings')) {
                $brandSetting = Setting::where('key', 'brand')->first();
            }
        } catch (\Throwable $e) {
            $brandSetting = null;
        }
        $brandLogoPath = $brandSetting?->value['logo'] ?? null;
        $brandLogoUrl = null;
        if ($brandLogoPath) {
            try {
                $brandLogoUrl = url('storage/'.ltrim($brandLogoPath, '/'));
            } catch (\Throwable $e) {
                $brandLogoUrl = null;
            }
        }
        if (! $brandLogoUrl) {
            try {
                $dir = public_path('storage/brand');
                if (is_dir($dir)) {
                    $files = glob($dir.'/*.{png,jpg,jpeg,svg}', GLOB_BRACE);
                    if ($files && count($files)) {
                        usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));
                        $file = basename($files[0]);
                        $brandLogoUrl = url('storage/brand/'.$file);
                    }
                }
            } catch (\Throwable $e) {
                $brandLogoUrl = null;
            }
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            function (): string {
                $url = url('/password/forgot');

                return '<div class="fi-help"><a href="'.$url.'">Esqueci minha senha · Recuperar por e-mail</a></div>'
                    .'<script>(function(){document.addEventListener("DOMContentLoaded",function(){var f=document.getElementById("form");if(!f)return;var actions=f.querySelector(".fi-form-actions");var rem=f.querySelector("[wire\\\\:key*=\'data.remember\']");if(actions&&rem){rem.classList.add("fi-remember");actions.insertAdjacentElement("afterend",rem);}});})();</script>';
            }
        );
        $brandLogoFile = null;
        if ($brandLogoPath) {
            $brandLogoFile = public_path('storage/'.ltrim($brandLogoPath, '/'));
        }
        if (! $brandLogoFile || ! file_exists($brandLogoFile)) {
            if (isset($file) && $file) {
                $brandLogoFile = public_path('storage/brand/'.basename($file));
            }
        }
        $primaryHex = '#009440';
        $accentHex = '#FFCC00';
        $secondaryHex = '#302681';
        $whiteHex = '#FFFFFF';
        $toRgb = function (string $hex): array {
            $hex = ltrim($hex, '#');
            if (strlen($hex) === 3) {
                $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            }

            return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
        };
        $toHex = function (int $r, int $g, int $b): string {
            $r = max(0, min(255, $r));
            $g = max(0, min(255, $g));
            $b = max(0, min(255, $b));

            return sprintf('#%02x%02x%02x', $r, $g, $b);
        };
        $darken = function (string $hex, float $f) use ($toRgb, $toHex): string {
            [$r,$g,$b] = $toRgb($hex);

            return $toHex(intval($r * (1 - $f)), intval($g * (1 - $f)), intval($b * (1 - $f)));
        };
        $lighten = function (string $hex, float $f) use ($toRgb, $toHex): string {
            [$r,$g,$b] = $toRgb($hex);

            return $toHex(intval($r + (255 - $r) * $f), intval($g + (255 - $g) * $f), intval($b + (255 - $b) * $f));
        };
        $p500 = $primaryHex;
        $p600 = $darken($p500, 0.12);
        $p700 = $darken($p500, 0.24);
        $p50 = $lighten($p500, 0.92);
        $a500 = $accentHex;
        $a600 = $darken($a500, 0.12);
        $a700 = $darken($a500, 0.24);
        $s600 = $lighten($secondaryHex, 0.08);
        $s700 = $darken($secondaryHex, 0.08);
        $s800 = $darken($secondaryHex, 0.18);
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            function () use ($p50, $p500, $p600, $p700, $a500, $a600, $a700, $s600, $s700, $s800, $whiteHex): string {
                $css = ':root{--primary-50:'.$p50.';--primary-500:'.$p500.';--primary-600:'.$p600.';--primary-700:'.$p700.';--accent-500:'.$a500.';--accent-600:'.$a600.';--accent-700:'.$a700.';--secondary-600:'.$s600.';--secondary-700:'.$s700.';--secondary-800:'.$s800.';--surface:'.$whiteHex.'}';
                $css .= '.fi-wi-stats-overview-stat-icon,.fi-wi-stats-overview-stat-description-icon{width:20px!important;height:20px!important}';
                $css .= '.fi-wi-stats-overview-stat-icon svg,.fi-wi-stats-overview-stat-description-icon svg{width:20px!important;height:20px!important}';
                $css .= '.fi-wi-stats-overview-stat-chart canvas{height:24px!important}';
                $css .= '.fi-wi-chart{max-width:100%}';
                $css .= '.fi-wi-chart canvas{max-height:260px!important}';
                $css .= '.fi-layout svg{width:20px!important;height:20px!important}';
                $css .= '.fi-fo-select select,.fi-input-wrp select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background:none!important;background-image:none!important;background-repeat:no-repeat!important;padding-right:2.25rem}';
                $css .= '.fi-fo-select .choices[data-type*="select-one"]::after,.fi-fo-select .choices[data-type*="select-multiple"]::after{content:none!important;display:none!important}';
                $css .= '.fi-fo-select .fi-input-wrp-suffix .fi-icon{display:none!important}';
                $css .= '.fi-fo-select .choices__inner{padding-right:2.25rem}';

                $links = '';
                try {
                    $supportCss = public_path('css/filament/support/support.css');
                    $formsCss = public_path('css/filament/forms/forms.css');
                    $appCss = public_path('css/filament/filament/app.css');
                    if (is_string($supportCss) && file_exists($supportCss)) {
                        $links .= '<link rel="stylesheet" href="'.asset('css/filament/support/support.css').'" />';
                    }
                    if (is_string($formsCss) && file_exists($formsCss)) {
                        $links .= '<link rel="stylesheet" href="'.asset('css/filament/forms/forms.css').'" />';
                    }
                    if (is_string($appCss) && file_exists($appCss)) {
                        $links .= '<link rel="stylesheet" href="'.asset('css/filament/filament/app.css').'" />';
                    }
                } catch (\Throwable $e) {
                }

                return '<style>'.$css.'</style>'.$links;
            }
        );

        $panelObj = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->login()
            ->sidebarCollapsibleOnDesktop(true)
            ->brandName('RCC Admin')
            ->brandLogo($brandLogoUrl)
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Gray,
                'warning' => Color::Amber,
                'success' => Color::Emerald,
            ])
            ->collapsibleNavigationGroups(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\PastoreioHistory::class,
                \App\Filament\Pages\PresencaRapida::class,
                \App\Filament\Pages\SettingsHome::class,
                \App\Filament\Pages\SettingsBrand::class,
                \App\Filament\Pages\SettingsSite::class,
                \App\Filament\Pages\SettingsEmail::class,
                \App\Filament\Pages\SettingsSms::class,
                \App\Filament\Pages\SettingsSocial::class,
                \App\Filament\Pages\SettingsMercadoPago::class,
                \App\Filament\Pages\SettingsWhatsApp::class,
                \App\Filament\Pages\SettingsTemplates::class,
            ])
            ->favicon(asset('favicon.ico'))
            ->navigationGroups([
                'Gerenciamento',
                'Eventos',
                'Logs',
                'Configurações',
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\AdminStatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                \App\Http\Middleware\AdminAccess::class,
                \Filament\Http\Middleware\Authenticate::class,
            ]);
        try {
            $manifest = public_path('build/manifest.json');
            if (is_string($manifest) && file_exists($manifest)) {
                $panelObj = $panelObj->viteTheme(['resources/css/filament/admin.css']);
            }
        } catch (\Throwable $e) {
        }

        app()->setLocale('pt_BR');
        app()->setLocale('pt_BR');

        return $panelObj;
    }
}
