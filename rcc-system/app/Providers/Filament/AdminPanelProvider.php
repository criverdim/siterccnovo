<?php

namespace App\Providers\Filament;

use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $brandSetting = null;
        if (Schema::hasTable('settings')) {
            $brandSetting = Setting::where('key', 'brand')->first();
        }
        $brandLogoPath = $brandSetting?->value['logo'] ?? null;
        $brandLogoUrl = $brandLogoPath ? \Illuminate\Support\Facades\Storage::disk('public')->url($brandLogoPath) : null;

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            function (): string {
                $url = url('/password/forgot');
                return '<div class="mt-6">'
                    .'<h2 class="text-sm font-semibold text-emerald-700 mb-2">Esqueci minha senha</h2>'
                    .'<a href="'.$url.'" class="text-sm text-primary-600 hover:underline">Recuperar senha por e-mail</a>'
                    .'</div>';
            }
        );

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->sidebarCollapsibleOnDesktop(true)
            ->brandName('RCC Admin')
            ->brandLogo($brandLogoUrl)
            ->brandLogoHeight('6rem')
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Gray,
                'warning' => Color::Amber,
                'success' => Color::Emerald,
            ])
            ->viteTheme(['resources/css/filament/admin.css'])
            ->collapsibleNavigationGroups(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\PastoreioHistory::class,
                \App\Filament\Pages\PresencaRapida::class,
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
                Authenticate::class,
                \App\Http\Middleware\AdminAccess::class,
            ]);
        app()->setLocale('pt_BR');

        // força tradução pt_BR para páginas do painel
        app()->setLocale('pt_BR');
    }
}
