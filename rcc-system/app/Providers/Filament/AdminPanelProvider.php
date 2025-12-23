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
use Illuminate\Support\Facades\Vite;
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

                return '<div class="fi-help"><a href="'.$url.'">Esqueci minha senha · Recuperar por e-mail</a></div>';
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
        $toRgbString = function (string $hex) use ($toRgb): string {
            [$r, $g, $b] = $toRgb($hex);

            return $r.' '.$g.' '.$b;
        };
        $p50Rgb = $toRgbString($p50);
        $p500Rgb = $toRgbString($p500);
        $p600Rgb = $toRgbString($p600);
        $p700Rgb = $toRgbString($p700);
        $a500Rgb = $toRgbString($a500);
        $a600Rgb = $toRgbString($a600);
        $a700Rgb = $toRgbString($a700);
        $s600Rgb = $toRgbString($s600);
        $s700Rgb = $toRgbString($s700);
        $s800Rgb = $toRgbString($s800);
        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_AFTER,
            function () use ($p50Rgb, $p500Rgb, $p600Rgb, $p700Rgb, $a500Rgb, $a600Rgb, $a700Rgb, $s600Rgb, $s700Rgb, $s800Rgb): string {
                static $cached = null;

                if ($cached !== null) {
                    return $cached;
                }

                $varsCss = ':root{--primary-50:'.$p50Rgb.';--primary-500:'.$p500Rgb.';--primary-600:'.$p600Rgb.';--primary-700:'.$p700Rgb.';--accent-500:'.$a500Rgb.';--accent-600:'.$a600Rgb.';--accent-700:'.$a700Rgb.';--secondary-600:'.$s600Rgb.';--secondary-700:'.$s700Rgb.';--secondary-800:'.$s800Rgb.'}';

                try {
                    $cached = '<style>'.$varsCss.'</style>'.Vite::toHtml(['resources/css/filament/admin.css']);

                    return $cached;
                } catch (\Throwable $e) {
                    $candidates = glob(public_path('build/assets/admin-*.css')) ?: [];

                    if (! count($candidates)) {
                        $candidates = glob(public_path('build/assets/admin*.css')) ?: [];
                    }

                    if (count($candidates)) {
                        usort($candidates, fn ($a, $b) => filemtime($b) <=> filemtime($a));
                        $file = basename($candidates[0]);

                        $cached = '<style>'.$varsCss.'</style><link rel="stylesheet" href="'.asset('build/assets/'.$file).'" />';

                        return $cached;
                    }

                    $cached = '';

                    return $cached;
                }
            }
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            function (): string {
                $css = 'html .fi [x-cloak]{display:initial!important}';
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
                $css .= '.fi-topbar a[href=\"/admin/settings\"],.fi-sidebar a[href=\"/admin/settings\"],.fi-header a[href=\"/admin/settings\"],.fi-breadcrumbs a[href=\"/admin/settings\"]{display:none!important}';
                $css .= '.fi-login-page .fi-form .fi-fo-actions .fi-btn,.fi-login-page .fi-form button[type=submit]{background:linear-gradient(90deg,#10b981,#059669)!important;color:#fff!important;border:none!important;border-radius:12px!important}';
                $css .= '.fi-login-page .fi-form .fi-fo-actions .fi-btn:hover,.fi-login-page .fi-form button[type=submit]:hover{filter:brightness(1.06)!important}';
                $css .= 'button.fi-btn,a.fi-btn{background:linear-gradient(90deg,#10b981,#059669)!important;color:#fff!important}';
                $css .= '.fi-btn.fi-btn-outlined{color:#059669!important;border-color:#059669!important}';

                return '<style>'.$css.'</style>';
            }
        );

        $panelObj = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')
            ->login()
            ->unsavedChangesAlerts(false)
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

        app()->setLocale('pt_BR');
        app()->setLocale('pt_BR');

        return $panelObj;
    }
}
