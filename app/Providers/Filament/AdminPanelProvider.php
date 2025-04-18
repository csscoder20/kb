<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Basic;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use App\Filament\Auth\CustomLoginPage;
use App\Filament\Auth\RegisterPage;



use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->darkMode(false)
            ->brandName(Basic::getValue('title'))
            ->darkModeBrandLogo(asset('storage/' . Basic::getValue('logo_dark')))
            ->brandLogo(asset('storage/' . Basic::getValue('logo_light')))
            ->brandLogoHeight('3rem')
            ->favicon(asset('storage/' . Basic::getValue('favicon')))
            ->registration(RegisterPage::class)
            // ->login(CustomLoginPage::class)
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->profile(false)
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'light' => Basic::getValue('dark_color') ?? Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Segoe UI')
            ->navigationGroups([
                'Report Management',
                'Settings',
                'Users Management'
            ])
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                \App\Filament\Pages\Emailsetting::class,
                \App\Filament\Pages\Basicsetting::class,
                // Register::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->renderHook('panels::auth.login.form.after', function () {
                return view('auth.socialite.google');
            })
            ->plugins([
                FilamentShieldPlugin::make(),
            ]);
    }
}
